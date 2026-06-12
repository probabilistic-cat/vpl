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

    public function testProductInfoBottom(): void {
        $beforeModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $productInfoBottom = $this->em->getRepository(ProductInfoBottom::class)
            ->find($this->productInfoBottom->getId())
        ;
        $this->assertSame($this->product->getId(), $productInfoBottom->getProduct()->getId());
        $this->assertSame($this->productInfoBottom->getName(), $productInfoBottom->getName());
        $this->assertSame($this->productInfoBottom->getSeq(), $productInfoBottom->getSeq());
        $this->assertTrue($productInfoBottom->getCreated()->getTimestamp() <= $beforeModifyTs);

        $productInfoBottom->setText(TestHelper::getRandomString());
        $this->em->persist($productInfoBottom);
        $this->em->flush();

        $afterModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $productInfoBottom2 = $this->em->getRepository(ProductInfoBottom::class)
            ->find($this->productInfoBottom->getId())
        ;
        $this->assertSame($productInfoBottom->getText(), $productInfoBottom2->getText());
        $this->assertEquals($productInfoBottom->getCreated(), $productInfoBottom2->getCreated());
        $this->assertNotNull($productInfoBottom2->getModified());
        $this->assertTrue($beforeModifyTs <= $productInfoBottom2->getModified()->getTimestamp());
        $this->assertTrue($productInfoBottom2->getModified()->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::$container->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category);
        $this->product = DBTestHelper::createProduct($this->em, $subcategory, 1);
        $this->productInfoBottom = DBTestHelper::createProductInfoBottom($this->em, $this->product, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->getId());
        $this->em->close();
        $this->em = null;
    }
}
