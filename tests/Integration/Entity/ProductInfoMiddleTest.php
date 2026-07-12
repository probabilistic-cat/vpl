<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductInfoMiddle;
use App\Tests\Integration\IntegrationTestCase;

class ProductInfoMiddleTest extends IntegrationTestCase
{
    private int $seq;

    private Category $category;
    private Product $product;
    private ProductInfoMiddle $productInfoMiddle;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productInfoMiddle);
        $this->assertSame($this->product->id, $this->productInfoMiddle->product->id);
        $this->assertSame($this->seq, $this->productInfoMiddle->seq);
        $this->assertFalse($this->productInfoMiddle->isGallery);
        $this->assertTrue($this->productInfoMiddle->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->productInfoMiddle->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productInfoMiddle);

        $name = $this->fixtureService->getRandomString();
        $text = $this->fixtureService->getRandomString();
        $created = $this->productInfoMiddle->created;

        $this->productInfoMiddle->name = $name;
        $this->productInfoMiddle->text = $text;
        $this->dbService->createProductInfoMiddleGallery($this->em, $this->productInfoMiddle, $this->fixtureService->getImgFile(), 1);
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productInfoMiddle);
        $this->assertSame($name, $this->productInfoMiddle->name);
        $this->assertSame($text, $this->productInfoMiddle->text);
        $this->assertTrue($this->productInfoMiddle->isGallery);
        $this->assertSame($created->getTimestamp(), $this->productInfoMiddle->created->getTimestamp());
        $this->assertNotNull($this->productInfoMiddle->modified);
        $this->assertTrue($beforeUpdateTs <= $this->productInfoMiddle->modified->getTimestamp());
        $this->assertTrue($this->productInfoMiddle->modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->refresh($this->productInfoMiddle);

        $this->assertSame(0, $this->productInfoMiddle->productInfoMiddleGalleries->count());
        $productInfoMiddleGallery = $this->dbService->createProductInfoMiddleGallery(
            $this->em,
            $this->productInfoMiddle,
            $this->fixtureService->getImgFile(),
            1,
        );
        $this->productInfoMiddle->addProductInfoMiddleGallery($productInfoMiddleGallery);
        $this->assertSame(1, $this->productInfoMiddle->productInfoMiddleGalleries->count());
        $this->productInfoMiddle->removeProductInfoMiddleGallery($productInfoMiddleGallery);
        $this->assertSame(0, $this->productInfoMiddle->productInfoMiddleGalleries->count());
    }

    protected function createObjects(): void {
        $this->seq = 1;

        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
        $this->product = $this->dbService->createProduct($this->em, $subcategory, $this->fixtureService->getRandomString(), 1);
        $this->productInfoMiddle = $this->dbService->createProductInfoMiddle($this->em, $this->product, $this->seq);
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
    }
}
