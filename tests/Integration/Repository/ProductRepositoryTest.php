<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\Subcategory;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use App\Tests\Integration\IntegrationTestCase;

class ProductRepositoryTest extends IntegrationTestCase
{
    private Category $category;
    private Subcategory $subcategory;
    private Product $product;
    private Manufacturer $manufacturer;

    public function testFindBySubcategoryManufacturer(): void {
        $this->em->clear();
        $productRepository = $this->em->getRepository(Product::class);
        $products = $productRepository
            ->findBySubcategoryManufacturer($this->subcategory->id, $this->manufacturer->id)
        ;
        $this->assertCount(1, $products);
        $product = $products[0];
        $this->assertInstanceOf(Product::class, $product);
        $this->assertSame($this->product->id, $product->id);
    }

    public function testGetSeqForNewProductInSubcategory(): void {
        $this->em->clear();
        $productRepository = $this->em->getRepository(Product::class);
        $newSeq = $productRepository->getSeqForNewProductInSubcategory($this->subcategory->id);
        $this->assertSame(2, $newSeq);
    }

    protected function createObjects(): void {
        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $this->subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
        $this->product = DBTestHelper::createProduct($this->em, $this->subcategory, TestHelper::getRandomString(), 1);
        $this->manufacturer = DBTestHelper::createManufacturer($this->em, TestHelper::getRandomString());
        DBTestHelper::createProductManufacturer($this->em, $this->product, $this->manufacturer, 1);
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer->id);
    }
}
