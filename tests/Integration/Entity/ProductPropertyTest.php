<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\CategoryProperty;
use App\Entity\Product;
use App\Entity\ProductProperty;
use App\Entity\Property;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;

class ProductPropertyTest extends KernelTestCase
{
    private EntityManagerInterface $em;

    private int $seq;

    private Category $category;
    private Product $product;
    private Property $property;
    private CategoryProperty $categoryProperty;
    private ProductProperty $productProperty;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productProperty);
        $this->assertSame($this->product->id, $this->productProperty->product->id);
        $this->assertSame($this->categoryProperty->id, $this->productProperty->categoryProperty->id);
        $this->assertSame($this->seq, $this->productProperty->seq);
        $this->assertTrue($this->productProperty->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->productProperty->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productProperty);

        $name = TestHelper::getRandomString();
        $imgFile = TestHelper::getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $this->productProperty->created;

        $this->em->refresh($this->property);
        $propertySet = DBTestHelper::createPropertySet($this->em, $this->property, TestHelper::getRandomString());
        $this->productProperty->propertySet = $propertySet;
        $this->productProperty->name = $name;
        $this->productProperty->imgFile = $imgFile;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productProperty);
        $this->assertSame($propertySet->id, $this->productProperty->propertySet->id);
        $this->assertSame($name, $this->productProperty->name);
        $imgFullPath = FileHelper::DIR_PUBLIC . $this->productProperty->img;
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->productProperty->created->getTimestamp());
        $this->assertNotNull($this->productProperty->modified);
        $this->assertTrue($beforeUpdateTs <= $this->productProperty->modified->getTimestamp());
        $this->assertTrue($this->productProperty->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $this->seq = 1;

        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
        $this->product = DBTestHelper::createProduct($this->em, $subcategory, TestHelper::getRandomString(), 1);
        $this->property = DBTestHelper::createProperty($this->em, TestHelper::getRandomString());
        $this->categoryProperty = DBTestHelper::createCategoryProperty($this->em, $this->category, $this->property, 1);
        $this->productProperty =
            DBTestHelper::createProductProperty($this->em, $this->product, $this->categoryProperty, $this->seq)
        ;
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteProperty($this->em, $this->property->id);
        $this->em->close();
    }
}
