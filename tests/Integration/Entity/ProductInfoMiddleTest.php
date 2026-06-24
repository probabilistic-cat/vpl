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
    private ?EntityManagerInterface $em;
    private Category $category;
    private Product $product;
    private ProductInfoMiddle $productInfoMiddle;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $productInfoMiddle = $this->em->getRepository(ProductInfoMiddle::class)->find($this->productInfoMiddle->id);
        $this->assertSame($this->product->id, $productInfoMiddle->product->id);
        $this->assertSame($this->productInfoMiddle->seq, $productInfoMiddle->seq);
        $this->assertSame(false, $productInfoMiddle->isGallery);
        $this->assertTrue($productInfoMiddle->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($productInfoMiddle->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $productInfoMiddle = $this->em->getRepository(ProductInfoMiddle::class)->find($this->productInfoMiddle->id);

        $name = TestHelper::getRandomString();
        $text = TestHelper::getRandomString();
        $created = $productInfoMiddle->created;

        $productInfoMiddle->name = $name;
        $productInfoMiddle->text = $text;
        DBTestHelper::createProductInfoMiddleGallery($this->em, $productInfoMiddle, 1);
        $this->em->persist($productInfoMiddle);
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $productInfoMiddle2 = $this->em->getRepository(ProductInfoMiddle::class)->find($this->productInfoMiddle->id);
        $this->assertSame($name, $productInfoMiddle2->name);
        $this->assertSame($text, $productInfoMiddle2->text);
        $this->assertTrue($productInfoMiddle2->isGallery);
        $this->assertSame($created->getTimestamp(), $productInfoMiddle2->created->getTimestamp());
        $this->assertNotNull($productInfoMiddle2->modified);
        $this->assertTrue($beforeUpdateTs <= $productInfoMiddle2->modified->getTimestamp());
        $this->assertTrue($productInfoMiddle2->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category);
        $this->product = DBTestHelper::createProduct($this->em, $subcategory, 1);
        $this->productInfoMiddle = DBTestHelper::createProductInfoMiddle($this->em, $this->product, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        $this->em->close();
        $this->em = null;
    }
}
