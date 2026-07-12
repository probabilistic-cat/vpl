<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\CategoryProperty;
use App\Entity\Property;
use App\Tests\Integration\IntegrationTestCase;

class CategoryPropertyTest extends IntegrationTestCase
{
    private const bool ACTIVE_DEFAULT = true;
    private const int LAYER_DEFAULT = 0;

    private int $seq;

    private Category $category;
    private Property $property;
    private CategoryProperty $categoryProperty;

    public function testRequiredProperties(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->categoryProperty);
        $this->assertSame($this->category->id, $this->categoryProperty->category->id);
        $this->assertSame($this->property->id, $this->categoryProperty->property->id);
        $this->assertSame($this->seq, $this->categoryProperty->seq);
        $this->assertSame(self::LAYER_DEFAULT, $this->categoryProperty->layer);
        $this->assertSame(self::ACTIVE_DEFAULT, $this->categoryProperty->active);
        $this->assertTrue($this->categoryProperty->created->getTimestamp() <= $beforeModify);
        $this->assertNull($this->categoryProperty->modified);
    }

    public function testUpdate(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->categoryProperty);

        $layer = 1;
        $active = !self::ACTIVE_DEFAULT;
        $created = $this->categoryProperty->created;

        $this->categoryProperty->layer = $layer;
        $this->categoryProperty->active = $active;
        $this->em->flush();

        $afterModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->categoryProperty);
        $this->assertSame($layer, $this->categoryProperty->layer);
        $this->assertSame($active, $this->categoryProperty->active);
        $this->assertSame($created->getTimestamp(), $this->categoryProperty->created->getTimestamp());
        $this->assertNotNull($this->categoryProperty->modified);
        $this->assertTrue($beforeModify <= $this->categoryProperty->modified->getTimestamp());
        $this->assertTrue($this->categoryProperty->modified->getTimestamp() <= $afterModify);
    }

    public function testCollections(): void {
        $this->em->refresh($this->category);
        $this->em->refresh($this->categoryProperty);

        $this->assertSame(0, $this->categoryProperty->productProperties->count());
        $subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
        $product = $this->dbService->createProduct($this->em, $subcategory, $this->fixtureService->getRandomString(), 1);
        $productProperty = $this->dbService->createProductProperty($this->em, $product, $this->categoryProperty, 1);
        $this->categoryProperty->addProductProperty($productProperty);
        $this->assertSame(1, $this->categoryProperty->productProperties->count());
        $this->categoryProperty->removeProductProperty($productProperty);
        $this->assertSame(0, $this->categoryProperty->productProperties->count());
    }

    protected function createObjects(): void {
        $this->seq = 1;

        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $this->property = $this->dbService->createProperty($this->em, $this->fixtureService->getRandomString());
        $this->categoryProperty =
            $this->dbService->createCategoryProperty($this->em, $this->category, $this->property, $this->seq)
        ;
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
        $this->dbService->deleteProperty($this->em, $this->property->id);
    }
}
