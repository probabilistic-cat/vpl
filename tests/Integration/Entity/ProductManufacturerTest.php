<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\ProductManufacturer;
use App\Tests\Integration\IntegrationTestCase;

class ProductManufacturerTest extends IntegrationTestCase
{
    private int $seq;

    private Category $category;
    private Product $product;
    private Manufacturer $manufacturer;
    private ProductManufacturer $productManufacturer;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productManufacturer);
        $this->assertSame($this->product->id, $this->productManufacturer->product->id);
        $this->assertSame($this->manufacturer->id, $this->productManufacturer->manufacturer->id);
        $this->assertSame($this->seq, $this->productManufacturer->seq);
        $this->assertTrue($this->productManufacturer->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->productManufacturer->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productManufacturer);

        $seq = 2;
        $created = $this->productManufacturer->created;

        $this->productManufacturer->seq = $seq;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productManufacturer);
        $this->assertSame($seq, $this->productManufacturer->seq);
        $this->assertSame($created->getTimestamp(), $this->productManufacturer->created->getTimestamp());
        $this->assertNotNull($this->productManufacturer->modified);
        $this->assertTrue($beforeUpdateTs <= $this->productManufacturer->modified->getTimestamp());
        $this->assertTrue($this->productManufacturer->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function createObjects(): void {
        $this->seq = 1;

        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
        $this->product = $this->dbService->createProduct($this->em, $subcategory, $this->fixtureService->getRandomString(), 1);
        $this->manufacturer = $this->dbService->createManufacturer($this->em, $this->fixtureService->getRandomString());
        $this->productManufacturer =
            $this->dbService->createProductManufacturer($this->em, $this->product, $this->manufacturer, $this->seq)
        ;
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
        $this->dbService->deleteManufacturer($this->em, $this->manufacturer->id);
    }
}
