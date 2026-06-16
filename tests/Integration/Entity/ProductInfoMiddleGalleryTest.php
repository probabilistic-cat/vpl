<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\ProductInfoMiddle;
use App\Entity\ProductInfoMiddleGallery;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductInfoMiddleGalleryTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;
    private ProductInfoMiddle $productInfoMiddle;
    private ProductInfoMiddleGallery $productInfoMiddleGallery;

    public function testProductInfoMiddleGallery(): void {
        $afterModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $productInfoMiddleGallery = $this->em->getRepository(ProductInfoMiddleGallery::class)
            ->find($this->productInfoMiddleGallery->getId())
        ;
        $this->assertSame(
            $this->productInfoMiddle->getId(),
            $productInfoMiddleGallery->getProductInfoMiddle()->getId(),
        );
        $this->assertSame($this->productInfoMiddleGallery->getSeq(), $productInfoMiddleGallery->getSeq());
        $this->assertSame($this->productInfoMiddleGallery->getImg(), $productInfoMiddleGallery->getImg());
        $this->assertFileExists(FileHelper::DIR_PUBLIC . $productInfoMiddleGallery->getImg());
        $this->assertTrue($productInfoMiddleGallery->getCreated()->getTimestamp() <= $afterModifyTs);
        $this->assertTrue($productInfoMiddleGallery->getModified()->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category);
        $product = DBTestHelper::createProduct($this->em, $subcategory, 1);
        $this->productInfoMiddle = DBTestHelper::createProductInfoMiddle($this->em, $product, 1);
        $this->productInfoMiddleGallery =
            DBTestHelper::createProductInfoMiddleGallery($this->em, $this->productInfoMiddle, 1)
        ;
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->getId());
        $this->em->close();
        $this->em = null;
    }
}
