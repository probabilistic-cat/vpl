<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Property;
use App\Tests\Integration\IntegrationTestCase;

class PropertyTest extends IntegrationTestCase
{
    private string $name;

    private Category $category;
    private Property $property;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->property);
        $this->assertSame($this->name, $this->property->name);
        $this->assertTrue($this->property->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->property->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->property);

        $name = $this->fixtureService->getRandomString();
        $created = $this->property->created;

        $this->property->name = $name;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->property);
        $this->assertSame($name, $this->property->name);
        $this->assertSame($created->getTimestamp(), $this->property->created->getTimestamp());
        $modified = $this->property->modified;
        $this->assertNotNull($modified);
        $this->assertTrue($beforeUpdateTs <= $modified->getTimestamp());
        $this->assertTrue($modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->refresh($this->category);
        $this->em->refresh($this->property);

        $this->assertCount(0, $this->property->categoryProperties);
        $categoryProperty = $this->dbService->createCategoryProperty($this->em, $this->category, $this->property, 1);
        $this->property->addCategoryProperty($categoryProperty);
        $this->assertCount(1, $this->property->categoryProperties);
        $this->property->removeCategoryProperty($categoryProperty);
        $this->assertCount(0, $this->property->categoryProperties);

        $this->assertCount(0, $this->property->propertySets);
        $propertySet = $this->dbService->createPropertySet($this->em, $this->property, $this->fixtureService->getRandomString());
        $this->property->addPropertySet($propertySet);
        $this->assertCount(1, $this->property->propertySets);
        $this->property->removePropertySet($propertySet);
        $this->assertCount(0, $this->property->propertySets);
    }

    protected function createObjects(): void {
        $this->name = $this->fixtureService->getRandomString();

        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $this->property = $this->dbService->createProperty($this->em, $this->name);
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
        $this->dbService->deleteProperty($this->em, $this->property->id);
    }
}
