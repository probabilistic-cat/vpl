<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Category;
use App\Entity\CategoryProperty;
use App\Entity\Property;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use App\Tests\Integration\IntegrationTestCase;

class CategoryPropertyRepositoryTest extends IntegrationTestCase
{
    private Category $category;
    private Property $property;
    private CategoryProperty $categoryProperty;

    public function testCreateCategoryQueryBuilder(): void {
        $this->em->clear();
        $categoryPropertyRepository = $this->em->getRepository(CategoryProperty::class);
        $categoryProperties = $categoryPropertyRepository
            ->getQBWithoutDesc($this->category)
            ->getQuery()
            ->getResult()
        ;
        $this->assertCount(1, $categoryProperties);
        $categoryProperty = $categoryProperties[0];
        $this->assertInstanceOf(CategoryProperty::class, $categoryProperty);
        $this->assertSame($this->categoryProperty->id, $categoryProperty->id);
    }

    protected function createObjects(): void {
        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $propertyBeschreibung = $this->em->getRepository(Property::class)
            ->findOneBy(['name' => Property::NAME_BESCHREIBUNG])
        ;
        DBTestHelper::createCategoryProperty($this->em, $this->category, $propertyBeschreibung, 1);
        $this->property = DBTestHelper::createProperty($this->em, TestHelper::getRandomString());
        $this->categoryProperty = DBTestHelper::createCategoryProperty($this->em, $this->category, $this->property, 2);
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteProperty($this->em, $this->property->id);
    }
}
