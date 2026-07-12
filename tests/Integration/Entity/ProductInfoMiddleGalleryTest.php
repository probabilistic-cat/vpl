<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\ProductInfoMiddle;
use App\Entity\ProductInfoMiddleGallery;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use App\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\File\File;

class ProductInfoMiddleGalleryTest extends IntegrationTestCase
{
    private string $imgFileContent;
    private int $seq;

    private Category $category;
    private ProductInfoMiddle $productInfoMiddle;
    private ProductInfoMiddleGallery $productInfoMiddleGallery;

    public function testRequiredProperties(): void {
        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productInfoMiddleGallery);
        $this->assertSame($this->productInfoMiddle->id, $this->productInfoMiddleGallery->productInfoMiddle->id);
        $this->assertSame($this->seq, $this->productInfoMiddleGallery->seq);
        $imgFullPath = $this->imageStorage->getAbsolutePath($this->productInfoMiddleGallery->img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($this->imgFileContent, new File($imgFullPath)->getContent());
        $this->assertTrue($this->productInfoMiddleGallery->created->getTimestamp() <= $afterUpdateTs);
        $this->assertTrue($this->productInfoMiddleGallery->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function createObjects(): void {
        $imgFile = TestHelper::getImgFile();
        $this->imgFileContent = $imgFile->getContent();
        $this->seq = 1;

        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
        $product = DBTestHelper::createProduct($this->em, $subcategory, TestHelper::getRandomString(), 1);
        $this->productInfoMiddle = DBTestHelper::createProductInfoMiddle($this->em, $product, 1);
        $this->productInfoMiddleGallery =
            DBTestHelper::createProductInfoMiddleGallery($this->em, $this->productInfoMiddle, $imgFile, $this->seq)
        ;
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteCategory($this->em, $this->category->id);
    }
}
