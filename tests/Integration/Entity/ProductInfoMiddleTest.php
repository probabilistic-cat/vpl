<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductInfoMiddle;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductInfoMiddleTest extends KernelTestCase
{
    private EntityManagerInterface $em;

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

        $name = TestHelper::getRandomString();
        $text = TestHelper::getRandomString();
        $created = $this->productInfoMiddle->created;

        $this->productInfoMiddle->name = $name;
        $this->productInfoMiddle->text = $text;
        DBTestHelper::createProductInfoMiddleGallery($this->em, $this->productInfoMiddle, TestHelper::getImgFile(), 1);
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
        $productInfoMiddleGallery = DBTestHelper::createProductInfoMiddleGallery(
            $this->em,
            $this->productInfoMiddle,
            TestHelper::getImgFile(),
            1,
        );
        $this->productInfoMiddle->addProductInfoMiddleGallery($productInfoMiddleGallery);
        $this->assertSame(1, $this->productInfoMiddle->productInfoMiddleGalleries->count());
        $this->productInfoMiddle->removeProductInfoMiddleGallery($productInfoMiddleGallery);
        $this->assertSame(0, $this->productInfoMiddle->productInfoMiddleGalleries->count());
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $this->seq = 1;

        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
        $this->product = DBTestHelper::createProduct($this->em, $subcategory, TestHelper::getRandomString(), 1);
        $this->productInfoMiddle = DBTestHelper::createProductInfoMiddle($this->em, $this->product, $this->seq);
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        $this->em->close();
    }
}
