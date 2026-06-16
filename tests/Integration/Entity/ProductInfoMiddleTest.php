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

    public function testProductInfoMiddle(): void {
        $beforeModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $productInfoMiddle = $this->em->getRepository(ProductInfoMiddle::class)
            ->find($this->productInfoMiddle->getId())
        ;
        $this->assertSame($this->product->getId(), $productInfoMiddle->getProduct()->getId());
        $this->assertSame($this->productInfoMiddle->getSeq(), $productInfoMiddle->getSeq());
        $this->assertSame(false, $productInfoMiddle->isGallery());
        $this->assertTrue($productInfoMiddle->getCreated()->getTimestamp() <= $beforeModifyTs);

        $productInfoMiddle->setName(TestHelper::getRandomString());
        $productInfoMiddle->setText(TestHelper::getRandomString());
        $productInfoMiddle->setIsGallery(true);
        $this->em->persist($productInfoMiddle);
        $this->em->flush();

        $afterModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $productInfoMiddle2 = $this->em->getRepository(ProductInfoMiddle::class)
            ->find($this->productInfoMiddle->getId())
        ;
        $this->assertSame($productInfoMiddle->getName(), $productInfoMiddle2->getName());
        $this->assertSame($productInfoMiddle->getText(), $productInfoMiddle2->getText());
        $this->assertSame($productInfoMiddle->isGallery(), $productInfoMiddle2->isGallery());
        $this->assertEquals($productInfoMiddle->getCreated(), $productInfoMiddle2->getCreated());
        $this->assertNotNull($productInfoMiddle2->getModified());
        $this->assertTrue($beforeModifyTs <= $productInfoMiddle2->getModified()->getTimestamp());
        $this->assertTrue($productInfoMiddle2->getModified()->getTimestamp() <= $afterModifyTs);
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
        DBTestHelper::deleteCategory($this->em, $this->category->getId());
        $this->em->close();
        $this->em = null;
    }
}
