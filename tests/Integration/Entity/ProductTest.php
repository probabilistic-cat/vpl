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
    private ?EntityManagerInterface $em;
    private Category $category;
    private Subcategory $subcategory;
    private Product $product;
    private Manufacturer $manufacturer;
    private Property $property;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $product = $this->em->getRepository(Product::class)->find($this->product->id);
        $this->assertSame($this->subcategory->id, $product->subcategory->id);
        $this->assertSame($this->product->name, $product->name);
        $this->assertSame($this->product->seq, $product->seq);
        $this->assertSame('Kammern (Rahmen)', $product->chambersName);
        $this->assertTrue($product->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($product->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $product = $this->em->getRepository(Product::class)->find($this->product->id);

        $description = TestHelper::getRandomString();
        $descriptionFull = TestHelper::getRandomString();
        $seals = TestHelper::getRandomString(2);
        $chambers = TestHelper::getRandomString(3);
        $chambersName = TestHelper::getRandomString();
        $imgFile = TestHelper::getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $product->created;

        $product->description = $description;
        $product->descriptionFull = $descriptionFull;
        $product->seals = $seals;
        $product->chambers = $chambers;
        $product->chambersName = $chambersName;
        $product->imgFile = $imgFile;
        $this->em->persist($product);
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $product2 = $this->em->getRepository(Product::class)->find($this->product->id);
        $imgFullPath = FileHelper::DIR_PUBLIC . $product2->img;
        $this->assertSame($description, $product2->description);
        $this->assertSame($descriptionFull, $product2->descriptionFull);
        $this->assertSame($seals, $product2->seals);
        $this->assertSame($chambers, $product2->chambers);
        $this->assertSame($chambersName, $product2->chambersName);
        $this->assertSame($product->img, $product2->img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $product2->created->getTimestamp());
        $this->assertNotNull($product2->modified);
        $this->assertTrue($beforeUpdateTs <= $product2->modified->getTimestamp());
        $this->assertTrue($product2->modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->clear();
        $category = $this->em->getRepository(Category::class)->find($this->category);
        $product = $this->em->getRepository(Product::class)->find($this->product->id);
        $manufacturer = $this->em->getRepository(Manufacturer::class)->find($this->manufacturer->id);
        $property = $this->em->getRepository(Property::class)->find($this->property->id);

        $this->assertSame(0, $product->productTypes->count());
        $productType = DBTestHelper::createProductType($this->em, $product, TestHelper::getRandomString(), 1);
        $product->addProductType($productType);
        $this->assertSame(1, $product->productTypes->count());
        $product->removeProductType($productType);
        $this->assertSame(0, $product->productTypes->count());

        $this->assertSame(0, $product->productProperties->count());
        $categoryProperty = DBTestHelper::createCategoryProperty($this->em, $category, $property, 1);
        $productProperty = DBTestHelper::createProductProperty($this->em, $product, $categoryProperty, 1);
        $product->addProductProperty($productProperty);
        $this->assertSame(1, $product->productProperties->count());
        $product->removeProductProperty($productProperty);
        $this->assertSame(0, $product->productProperties->count());

        $this->assertSame(0, $product->productInfoMiddles->count());
        $productInfoMiddle = DBTestHelper::createProductInfoMiddle($this->em, $product, 1);
        $product->addProductInfoMiddle($productInfoMiddle);
        $this->assertSame(1, $product->productInfoMiddles->count());
        $product->removeProductInfoMiddle($productInfoMiddle);
        $this->assertSame(0, $product->productInfoMiddles->count());

        $this->assertSame(0, $product->productInfoBottoms->count());
        $productInfoBottom = DBTestHelper::createProductInfoBottom($this->em, $product, 1);
        $product->addProductInfoBottom($productInfoBottom);
        $this->assertSame(1, $product->productInfoBottoms->count());
        $product->removeProductInfoBottom($productInfoBottom);
        $this->assertSame(0, $product->productInfoBottoms->count());

        $this->assertSame(0, $product->productManufacturers->count());
        $productManufacturer = DBTestHelper::createProductManufacturer($this->em, $product, $manufacturer, 1);
        $product->addProductManufacturer($productManufacturer);
        $this->assertSame(1, $product->productManufacturers->count());
        $product->removeProductManufacturer($productManufacturer);
        $this->assertSame(0, $product->productManufacturers->count());
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $this->subcategory = DBTestHelper::createSubcategory($this->em, $this->category);
        $this->product = DBTestHelper::createProduct($this->em, $this->subcategory, 1);
        $this->manufacturer = DBTestHelper::createManufacturer($this->em);
        $this->property = DBTestHelper::createProperty($this->em);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer->id);
        DBTestHelper::deleteProperty($this->em, $this->property->id);
        $this->em->close();
        $this->em = null;
    }
}
