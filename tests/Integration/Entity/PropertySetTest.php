<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Property;
use App\Entity\PropertySet;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use App\Tests\Integration\IntegrationTestCase;

class PropertySetTest extends IntegrationTestCase
{
    private string $name;

    private Category $category;
    private Property $property;
    private PropertySet $propertySet;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->propertySet);
        $this->assertSame($this->property->id, $this->propertySet->property->id);
        $this->assertSame($this->name, $this->propertySet->name);
        $this->assertTrue($this->propertySet->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->propertySet->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->propertySet);

        $name = TestHelper::getRandomString();
        $created = $this->propertySet->created;

        $this->propertySet->name = $name;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->propertySet);
        $this->assertSame($name, $this->propertySet->name);
        $this->assertSame($created->getTimestamp(), $this->propertySet->created->getTimestamp());
        $this->assertNotNull($this->propertySet->modified);
        $this->assertTrue($beforeUpdateTs <= $this->propertySet->modified->getTimestamp());
        $this->assertTrue($this->propertySet->modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->refresh($this->category);
        $this->em->refresh($this->property);
        $this->em->refresh($this->propertySet);

        $this->assertSame(0, $this->propertySet->propertyItems->count());
        $propertyItem = DBTestHelper::createPropertyItem($this->em, $this->propertySet, TestHelper::getImgFile(), 1);
        $this->propertySet->addPropertyItem($propertyItem);
        $this->assertSame(1, $this->propertySet->propertyItems->count());
        $this->propertySet->removePropertyItem($propertyItem);
        $this->assertSame(0, $this->propertySet->propertyItems->count());

        $this->assertSame(0, $this->propertySet->productProperties->count());
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
        $product = DBTestHelper::createProduct($this->em, $subcategory, TestHelper::getRandomString(), 1);
        $categoryProperty = DBTestHelper::createCategoryProperty($this->em, $this->category, $this->property, 1);
        $productProperty = DBTestHelper::createProductProperty($this->em, $product, $categoryProperty, 1);
        $this->propertySet->addProductProperty($productProperty);
        $this->assertSame(1, $this->propertySet->productProperties->count());
        $this->propertySet->removeProductProperty($productProperty);
        $this->assertSame(0, $this->propertySet->productProperties->count());
    }

    protected function createObjects(): void {
        $this->name = TestHelper::getRandomString();

        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $this->property = DBTestHelper::createProperty($this->em, TestHelper::getRandomString());
        $this->propertySet = DBTestHelper::createPropertySet($this->em, $this->property, $this->name);
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteProperty($this->em, $this->property->id);
    }
}
