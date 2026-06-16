<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\CategoryProperty;
use App\Entity\Product;
use App\Entity\ProductProperty;
use App\Entity\Property;
use App\Entity\PropertySet;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductPropertyTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;
    private Product $product;
    private Property $property;
    private CategoryProperty $categoryProperty;
    private PropertySet $propertySet;
    private ProductProperty $productProperty;

    public function testProductProperty(): void {
        $beforeModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $productProperty = $this->em->getRepository(ProductProperty::class)->find($this->productProperty->getId());
        $this->assertSame($this->product->getId(), $productProperty->getProduct()->getId());
        $this->assertSame($this->categoryProperty->getId(), $productProperty->getCategoryProperty()->getId());
        $this->assertSame($this->productProperty->getSeq(), $productProperty->getSeq());
        $this->assertTrue($productProperty->getCreated()->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($productProperty->getModified());

        $propertySet = $this->em->getRepository(PropertySet::class)->find($this->propertySet->getId());
        $productProperty->setPropertySet($propertySet);
        $productProperty->setName(TestHelper::getRandomString());
        $productProperty->setImgFile(TestHelper::getImgFile());
        $this->em->persist($productProperty);
        $this->em->flush();

        $afterModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $productProperty2 = $this->em->getRepository(ProductProperty::class)->find($this->productProperty->getId());
        $this->assertSame($this->propertySet->getId(), $productProperty2->getPropertySet()->getId());
        $this->assertSame($productProperty->getName(), $productProperty2->getName());
        $this->assertSame($productProperty->getImg(), $productProperty2->getImg());
        $this->assertFileExists(FileHelper::DIR_PUBLIC . $productProperty2->getImg());
        $this->assertEquals($productProperty->getCreated(), $productProperty2->getCreated());
        $this->assertNotNull($productProperty2->getModified());
        $this->assertTrue($beforeModifyTs <= $productProperty2->getModified()->getTimestamp());
        $this->assertTrue($productProperty2->getModified()->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category);
        $this->product = DBTestHelper::createProduct($this->em, $subcategory, 1);
        $this->property = DBTestHelper::createProperty($this->em);
        $this->categoryProperty = DBTestHelper::createCategoryProperty($this->em, $this->category, $this->property, 1);
        $this->propertySet = DBTestHelper::createPropertySet($this->em, $this->property);
        $this->productProperty =
            DBTestHelper::createProductProperty($this->em, $this->product, $this->categoryProperty, 1)
        ;
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->getId());
        DBTestHelper::deleteProperty($this->em, $this->property->getId());
        $this->em->close();
        $this->em = null;
    }
}
