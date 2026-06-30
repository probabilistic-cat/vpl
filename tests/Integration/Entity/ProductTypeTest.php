<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductType;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
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

        $imgFile = TestHelper::getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $this->productType->created;

        $this->productType->imgFile = $imgFile;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productType);
        $imgFullPath = FileHelper::DIR_PUBLIC . $this->productType->img;
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->productType->created->getTimestamp());
        $this->assertNotNull($this->productType->modified);
        $this->assertTrue($beforeUpdateTs <= $this->productType->modified->getTimestamp());
        $this->assertTrue($this->productType->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function createObjects(): void {
        $this->text = TestHelper::getRandomString();
        $this->seq = 1;

        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
        $this->product = DBTestHelper::createProduct($this->em, $subcategory, TestHelper::getRandomString(), 1);
        $this->productType = DBTestHelper::createProductType($this->em, $this->product, $this->text, $this->seq);
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteCategory($this->em, $this->category->id);
    }
}
