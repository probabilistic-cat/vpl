<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Property;
use App\Entity\PropertySet;
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

        $name = $this->fixtureService->getRandomString();
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
        $propertyItem = $this->dbService->createPropertyItem($this->em, $this->propertySet, $this->fixtureService->getImgFile(), 1);
        $this->propertySet->addPropertyItem($propertyItem);
        $this->assertSame(1, $this->propertySet->propertyItems->count());
        $this->propertySet->removePropertyItem($propertyItem);
        $this->assertSame(0, $this->propertySet->propertyItems->count());

        $this->assertSame(0, $this->propertySet->productProperties->count());
        $subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
        $product = $this->dbService->createProduct($this->em, $subcategory, $this->fixtureService->getRandomString(), 1);
        $categoryProperty = $this->dbService->createCategoryProperty($this->em, $this->category, $this->property, 1);
        $productProperty = $this->dbService->createProductProperty($this->em, $product, $categoryProperty, 1);
        $this->propertySet->addProductProperty($productProperty);
        $this->assertSame(1, $this->propertySet->productProperties->count());
        $this->propertySet->removeProductProperty($productProperty);
        $this->assertSame(0, $this->propertySet->productProperties->count());
    }

    protected function createObjects(): void {
        $this->name = $this->fixtureService->getRandomString();

        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $this->property = $this->dbService->createProperty($this->em, $this->fixtureService->getRandomString());
        $this->propertySet = $this->dbService->createPropertySet($this->em, $this->property, $this->name);
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
        $this->dbService->deleteProperty($this->em, $this->property->id);
    }
}
