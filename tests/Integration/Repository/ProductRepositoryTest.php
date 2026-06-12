<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\Subcategory;
use App\Tests\Helper\DBTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;
    private Subcategory $subcategory;
    private Product $product;
    private Manufacturer $manufacturer;

    public function testFindAllOrderedByName(): void {
        $this->em->clear();
        $productRepository = $this->em->getRepository(Product::class);
        $products = $productRepository->findAllOrderedByName();
        $this->assertNotEmpty($products);
        $matchedProducts = array_filter($products, fn($product) => $product->getId() === $this->product->getId());
        $this->assertGreaterThanOrEqual(1, count($matchedProducts));
    }

    public function testFindBySubcategoryManufacturer(): void {
        $this->em->clear();
        $productRepository = $this->em->getRepository(Product::class);
        $products = $productRepository
            ->findBySubcategoryManufacturer($this->subcategory->getId(), $this->manufacturer->getId())
        ;
        $this->assertCount(1, $products);
        $product = $products[0];
        $this->assertInstanceOf(Product::class, $product);
        $this->assertSame($this->product->getId(), $product->getId());
    }

    public function testFindBySubcategory(): void {
        $this->em->clear();
        $productRepository = $this->em->getRepository(Product::class);
        $products = $productRepository->findBySubcategory($this->subcategory->getId());
        $this->assertCount(1, $products);
        $product = $products[0];
        $this->assertInstanceOf(Product::class, $product);
        $this->assertSame($this->product->getId(), $product->getId());
    }

    public function testGetSeqForNewProductInSubcategory(): void {
        $this->em->clear();
        $productRepository = $this->em->getRepository(Product::class);
        $newSeq = $productRepository->getSeqForNewProductInSubcategory($this->subcategory->getId());
        $this->assertSame(2, $newSeq);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::$container->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $this->subcategory = DBTestHelper::createSubcategory($this->em, $this->category);
        $this->product = DBTestHelper::createProduct($this->em, $this->subcategory, 1);
        $this->manufacturer = DBTestHelper::createManufacturer($this->em);
        DBTestHelper::createProductManufacturer($this->em, $this->product, $this->manufacturer, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteCategory($this->em, $this->category->getId());
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer->getId());
        $this->em->close();
        $this->em = null;
    }
}
