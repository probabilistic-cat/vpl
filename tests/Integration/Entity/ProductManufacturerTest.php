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
    private Manufacturer  $manufacturer;
    private ProductManufacturer  $productManufacturer;

    public function testProductManufacturer(): void {
        $beforeModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $productManufacturer = $this->em->getRepository(ProductManufacturer::class)
            ->find($this->productManufacturer->getId())
        ;
        $this->assertSame($this->product->getId(), $productManufacturer->getProduct()->getId());
        $this->assertSame($this->manufacturer->getId(), $productManufacturer->getManufacturer()->getId());
        $this->assertSame($this->productManufacturer->getSeq(), $productManufacturer->getSeq());
        $this->assertTrue($productManufacturer->getCreated()->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($productManufacturer->getModified());

        $productManufacturer->setSeq(2);
        $this->em->persist($productManufacturer);
        $this->em->flush();

        $afterModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $productManufacturer2 = $this->em->getRepository(ProductManufacturer::class)
            ->find($this->productManufacturer->getId())
        ;
        $this->assertSame($productManufacturer->getSeq(), $productManufacturer2->getSeq());
        $this->assertEquals($productManufacturer->getCreated(), $productManufacturer2->getCreated());
        $this->assertNotNull($productManufacturer2->getModified());
        $this->assertTrue($beforeModifyTs <= $productManufacturer2->getModified()->getTimestamp());
        $this->assertTrue($productManufacturer2->getModified()->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::$container->get(EntityManagerInterface::class);
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
        DBTestHelper::deleteCategory($this->em, $this->category->getId());
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer->getId());
        $this->em->close();
        $this->em = null;
    }
}
