<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\ProductManufacturer;
use App\Entity\Subcategory;
use App\Tests\Helper\DBTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductManufacturerTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;
    private Subcategory $subcategory;
    private Product $product;
    private Manufacturer $manufacturer;
    private ProductManufacturer $productManufacturer;

    public function testProductManufacturer(): void {
        $beforeModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $productManufacturer = $this->em->getRepository(ProductManufacturer::class)
            ->find($this->productManufacturer)
        ;
        $this->assertSame($this->product->id, $productManufacturer->product->id);
        $this->assertSame($this->manufacturer->id, $productManufacturer->manufacturer->id);
        $this->assertSame($this->productManufacturer->seq, $productManufacturer->seq);
        $this->assertTrue($productManufacturer->created->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($productManufacturer->modified);

        $productManufacturer->seq = 2;
        $this->em->persist($productManufacturer);
        $this->em->flush();

        $afterModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $productManufacturer2 = $this->em->getRepository(ProductManufacturer::class)
            ->find($this->productManufacturer)
        ;
        $this->assertSame($productManufacturer->seq, $productManufacturer2->seq);
        $this->assertEquals($productManufacturer->created, $productManufacturer2->created);
        $this->assertNotNull($productManufacturer2->modified);
        $this->assertTrue($beforeModifyTs <= $productManufacturer2->modified->getTimestamp());
        $this->assertTrue($productManufacturer2->modified->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $this->subcategory = DBTestHelper::createSubcategory($this->em, $this->category);
        $this->product = DBTestHelper::createProduct($this->em, $this->subcategory, 1);
        $this->manufacturer = DBTestHelper::createManufacturer($this->em);
        $this->productManufacturer =
            DBTestHelper::createProductManufacturer($this->em, $this->product, $this->manufacturer, 1)
        ;
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer->id);
        $this->em->close();
        $this->em = null;
    }
}
