<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\ProductManufacturer;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductManufacturerTest extends KernelTestCase
{
    private EntityManagerInterface $em;

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

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $this->seq = 1;

        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
        $this->product = DBTestHelper::createProduct($this->em, $subcategory, TestHelper::getRandomString(), 1);
        $this->manufacturer = DBTestHelper::createManufacturer($this->em, TestHelper::getRandomString());
        $this->productManufacturer =
            DBTestHelper::createProductManufacturer($this->em, $this->product, $this->manufacturer, $this->seq)
        ;
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer->id);
        $this->em->close();
    }
}
