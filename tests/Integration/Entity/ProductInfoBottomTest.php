<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductInfoBottom;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductInfoBottomTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;
    private Product $product;
    private ProductInfoBottom $productInfoBottom;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $productInfoBottom = $this->em->getRepository(ProductInfoBottom::class)->find($this->productInfoBottom->id);
        $this->assertSame($this->product->id, $productInfoBottom->product->id);
        $this->assertSame($this->productInfoBottom->name, $productInfoBottom->name);
        $this->assertSame($this->productInfoBottom->seq, $productInfoBottom->seq);
        $this->assertTrue($productInfoBottom->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($productInfoBottom->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $productInfoBottom = $this->em->getRepository(ProductInfoBottom::class)->find($this->productInfoBottom->id);

        $text = TestHelper::getRandomString();
        $created = $productInfoBottom->created;

        $productInfoBottom->text = $text;
        $this->em->persist($productInfoBottom);
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $productInfoBottom2 = $this->em->getRepository(ProductInfoBottom::class)->find($this->productInfoBottom->id);
        $this->assertSame($text, $productInfoBottom2->text);
        $this->assertSame($created->getTimestamp(), $productInfoBottom2->created->getTimestamp());
        $this->assertNotNull($productInfoBottom2->modified);
        $this->assertTrue($beforeUpdateTs <= $productInfoBottom2->modified->getTimestamp());
        $this->assertTrue($productInfoBottom2->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category);
        $this->product = DBTestHelper::createProduct($this->em, $subcategory, 1);
        $this->productInfoBottom = DBTestHelper::createProductInfoBottom($this->em, $this->product, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        $this->em->close();
        $this->em = null;
    }
}
