<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductType;
use App\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\File\File;

class ProductTypeTest extends IntegrationTestCase
{
    private string $text;
    private int $seq;

    private Category $category;
    private Product $product;
    private ProductType $productType;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productType);
        $this->assertSame($this->product->id, $this->productType->product->id);
        $this->assertSame($this->text, $this->productType->text);
        $this->assertSame($this->seq, $this->productType->seq);
        $this->assertTrue($this->productType->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->productType->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productType);

        $imgFile = $this->fixtureService->getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $this->productType->created;

        $this->productType->imgFile = $imgFile;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productType);
        $imgFullPath = $this->imageStorage->getAbsolutePath($this->productType->img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->productType->created->getTimestamp());
        $this->assertNotNull($this->productType->modified);
        $this->assertTrue($beforeUpdateTs <= $this->productType->modified->getTimestamp());
        $this->assertTrue($this->productType->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function createObjects(): void {
        $this->text = $this->fixtureService->getRandomString();
        $this->seq = 1;

        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
        $this->product = $this->dbService->createProduct($this->em, $subcategory, $this->fixtureService->getRandomString(), 1);
        $this->productType = $this->dbService->createProductType($this->em, $this->product, $this->text, $this->seq);
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
    }
}
