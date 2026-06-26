<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\Property;
use App\Entity\Subcategory;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;

class ProductTest extends KernelTestCase
{
    private EntityManagerInterface $em;

    private string $name;
    private int $seq;

    private Category $category;
    private Subcategory $subcategory;
    private Product $product;
    private Manufacturer $manufacturer;
    private Property $property;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->product);
        $this->assertSame($this->subcategory->id, $this->product->subcategory->id);
        $this->assertSame($this->name, $this->product->name);
        $this->assertSame($this->seq, $this->product->seq);
        $this->assertSame('Kammern (Rahmen)', $this->product->chambersName);
        $this->assertTrue($this->product->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->product->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->product);

        $description = TestHelper::getRandomString();
        $descriptionFull = TestHelper::getRandomString();
        $seals = TestHelper::getRandomString(2);
        $chambers = TestHelper::getRandomString(3);
        $chambersName = TestHelper::getRandomString();
        $imgFile = TestHelper::getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $this->product->created;

        $this->product->description = $description;
        $this->product->descriptionFull = $descriptionFull;
        $this->product->seals = $seals;
        $this->product->chambers = $chambers;
        $this->product->chambersName = $chambersName;
        $this->product->imgFile = $imgFile;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->product);
        $this->assertSame($description, $this->product->description);
        $this->assertSame($descriptionFull, $this->product->descriptionFull);
        $this->assertSame($seals, $this->product->seals);
        $this->assertSame($chambers, $this->product->chambers);
        $this->assertSame($chambersName, $this->product->chambersName);
        $imgFullPath = FileHelper::DIR_PUBLIC . $this->product->img;
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->product->created->getTimestamp());
        $this->assertNotNull($this->product->modified);
        $this->assertTrue($beforeUpdateTs <= $this->product->modified->getTimestamp());
        $this->assertTrue($this->product->modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->refresh($this->category);
        $this->em->refresh($this->product);
        $this->em->refresh($this->manufacturer);
        $this->em->refresh($this->property);

        $this->assertSame(0, $this->product->productTypes->count());
        $productType = DBTestHelper::createProductType($this->em, $this->product, TestHelper::getRandomString(), 1);
        $this->product->addProductType($productType);
        $this->assertSame(1, $this->product->productTypes->count());
        $this->product->removeProductType($productType);
        $this->assertSame(0, $this->product->productTypes->count());

        $this->assertSame(0, $this->product->productProperties->count());
        $categoryProperty = DBTestHelper::createCategoryProperty($this->em, $this->category, $this->property, 1);
        $productProperty = DBTestHelper::createProductProperty($this->em, $this->product, $categoryProperty, 1);
        $this->product->addProductProperty($productProperty);
        $this->assertSame(1, $this->product->productProperties->count());
        $this->product->removeProductProperty($productProperty);
        $this->assertSame(0, $this->product->productProperties->count());

        $this->assertSame(0, $this->product->productInfoMiddles->count());
        $productInfoMiddle = DBTestHelper::createProductInfoMiddle($this->em, $this->product, 1);
        $this->product->addProductInfoMiddle($productInfoMiddle);
        $this->assertSame(1, $this->product->productInfoMiddles->count());
        $this->product->removeProductInfoMiddle($productInfoMiddle);
        $this->assertSame(0, $this->product->productInfoMiddles->count());

        $this->assertSame(0, $this->product->productInfoBottoms->count());
        $productInfoBottom = DBTestHelper::createProductInfoBottom($this->em, $this->product, TestHelper::getRandomString(), 1);
        $this->product->addProductInfoBottom($productInfoBottom);
        $this->assertSame(1, $this->product->productInfoBottoms->count());
        $this->product->removeProductInfoBottom($productInfoBottom);
        $this->assertSame(0, $this->product->productInfoBottoms->count());

        $this->assertSame(0, $this->product->productManufacturers->count());
        $productManufacturer = DBTestHelper::createProductManufacturer($this->em, $this->product, $this->manufacturer, 1);
        $this->product->addProductManufacturer($productManufacturer);
        $this->assertSame(1, $this->product->productManufacturers->count());
        $this->product->removeProductManufacturer($productManufacturer);
        $this->assertSame(0, $this->product->productManufacturers->count());
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $this->name = TestHelper::getRandomString();
        $this->seq = 1;

        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $this->subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
        $this->product = DBTestHelper::createProduct($this->em, $this->subcategory, $this->name, $this->seq);
        $this->manufacturer = DBTestHelper::createManufacturer($this->em, TestHelper::getRandomString());
        $this->property = DBTestHelper::createProperty($this->em, TestHelper::getRandomString());
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer->id);
        DBTestHelper::deleteProperty($this->em, $this->property->id);
        $this->em->close();
    }
}
