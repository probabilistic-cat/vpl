<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Property;
use App\Entity\PropertySet;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PropertySetTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;
    private Property $property;
    private PropertySet $propertySet;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $propertySet = $this->em->getRepository(PropertySet::class)->find($this->propertySet->id);
        $this->assertSame($this->property->id, $propertySet->property->id);
        $this->assertSame($this->propertySet->name, $propertySet->name);
        $this->assertTrue($propertySet->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($propertySet->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $propertySet = $this->em->getRepository(PropertySet::class)->find($this->propertySet->id);

        $name = TestHelper::getRandomString();
        $created = $propertySet->created;

        $propertySet->name = $name;
        $this->em->persist($propertySet);
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $propertySet2 = $this->em->getRepository(PropertySet::class)->find($this->propertySet->id);
        $this->assertSame($name, $propertySet2->name);
        $this->assertSame($created->getTimestamp(), $propertySet2->created->getTimestamp());
        $this->assertNotNull($propertySet2->modified);
        $this->assertTrue($beforeUpdateTs <= $propertySet2->modified->getTimestamp());
        $this->assertTrue($propertySet2->modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->clear();
        $category = $this->em->getRepository(Category::class)->find($this->category);
        $property = $this->em->getRepository(Property::class)->find($this->property->id);
        $propertySet = $this->em->getRepository(PropertySet::class)->find($this->propertySet->id);

        $this->assertSame(0, $propertySet->propertyItems->count());
        $propertyItem = DBTestHelper::createPropertyItem($this->em, $propertySet, 1);
        $propertySet->addPropertyItem($propertyItem);
        $this->assertSame(1, $propertySet->propertyItems->count());
        $propertySet->removePropertyItem($propertyItem);
        $this->assertSame(0, $propertySet->propertyItems->count());

        $this->assertSame(0, $propertySet->productProperties->count());
        $subcategory = DBTestHelper::createSubcategory($this->em, $category);
        $product = DBTestHelper::createProduct($this->em, $subcategory, 1);
        $categoryProperty = DBTestHelper::createCategoryProperty($this->em, $category, $property, 1);
        $productProperty = DBTestHelper::createProductProperty($this->em, $product, $categoryProperty, 1);
        $propertySet->addProductProperty($productProperty);
        $this->assertSame(1, $propertySet->productProperties->count());
        $propertySet->removeProductProperty($productProperty);
        $this->assertSame(0, $propertySet->productProperties->count());
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $this->property = DBTestHelper::createProperty($this->em);
        $this->propertySet = DBTestHelper::createPropertySet($this->em, $this->property);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteProperty($this->em, $this->property->id);
        $this->em->close();
        $this->em = null;
    }
}
