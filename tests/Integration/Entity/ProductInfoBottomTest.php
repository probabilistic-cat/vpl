<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductInfoBottom;
use App\Tests\Integration\IntegrationTestCase;

class ProductInfoBottomTest extends IntegrationTestCase
{
    private string $name;
    private int $seq;

    private Category $category;
    private Product $product;
    private ProductInfoBottom $productInfoBottom;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productInfoBottom);
        $this->assertSame($this->product->id, $this->productInfoBottom->product->id);
        $this->assertSame($this->name, $this->productInfoBottom->name);
        $this->assertSame($this->seq, $this->productInfoBottom->seq);
        $this->assertTrue($this->productInfoBottom->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->productInfoBottom->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productInfoBottom);

        $text = $this->fixtureService->getRandomString();
        $created = $this->productInfoBottom->created;

        $this->productInfoBottom->text = $text;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productInfoBottom);
        $this->assertSame($text, $this->productInfoBottom->text);
        $this->assertSame($created->getTimestamp(), $this->productInfoBottom->created->getTimestamp());
        $this->assertNotNull($this->productInfoBottom->modified);
        $this->assertTrue($beforeUpdateTs <= $this->productInfoBottom->modified->getTimestamp());
        $this->assertTrue($this->productInfoBottom->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function createObjects(): void {
        $this->name = $this->fixtureService->getRandomString();
        $this->seq = 1;

        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
        $this->product = $this->dbService->createProduct($this->em, $subcategory, $this->fixtureService->getRandomString(), 1);
        $this->productInfoBottom = $this->dbService->createProductInfoBottom(
            $this->em,
            $this->product,
            $this->name,
            $this->seq,
        );
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
    }
}
