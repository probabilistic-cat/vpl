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
    private EntityManagerInterface $em;

    private string $name;
    private int $seq;

    private Category $category;
    private Product $product;
    private ProductInfoBottom $productInfoBottom;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productInfoBottom);
        $this->assertSame($this->product->id, $this->productInfoBottom->product->id);
        $this->assertSame($this->name, $this->productInfoBottom->name);
        $this->assertSame($this->seq, $this->productInfoBottom->seq);
        $this->assertTrue($this->productInfoBottom->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->productInfoBottom->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productInfoBottom);

        $text = TestHelper::getRandomString();
        $created = $this->productInfoBottom->created;

        $this->productInfoBottom->text = $text;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productInfoBottom);
        $this->assertSame($text, $this->productInfoBottom->text);
        $this->assertSame($created->getTimestamp(), $this->productInfoBottom->created->getTimestamp());
        $this->assertNotNull($this->productInfoBottom->modified);
        $this->assertTrue($beforeUpdateTs <= $this->productInfoBottom->modified->getTimestamp());
        $this->assertTrue($this->productInfoBottom->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $this->name = TestHelper::getRandomString();
        $this->seq = 1;

        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
        $this->product = DBTestHelper::createProduct($this->em, $subcategory, TestHelper::getRandomString(), 1);
        $this->productInfoBottom = DBTestHelper::createProductInfoBottom(
            $this->em,
            $this->product,
            $this->name,
            $this->seq,
        );
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        $this->em->close();
    }
}
