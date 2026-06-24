<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductType;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductTypeTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;
    private Product $product;
    private ProductType $productType;

    public function testProductType(): void {
        $beforeModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $productType = $this->em->getRepository(ProductType::class)->find($this->productType);
        $this->assertSame($this->product->id, $productType->product->id);
        $this->assertSame($this->productType->text, $productType->text);
        $this->assertSame($this->productType->seq, $productType->seq);
        $this->assertTrue($productType->created->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($productType->modified);

        $productType->imgFile = TestHelper::getImgFile();
        $this->em->persist($productType);
        $this->em->flush();

        $afterModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $productType2 = $this->em->getRepository(ProductType::class)->find($this->productType);
        $this->assertSame($productType->img, $productType2->img);
        $this->assertFileExists(FileHelper::DIR_PUBLIC . $productType2->img);
        $this->assertEquals($productType->created, $productType2->created);
        $this->assertNotNull($productType2->modified);
        $this->assertTrue($beforeModifyTs <= $productType2->modified->getTimestamp());
        $this->assertTrue($productType2->modified->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category);
        $this->product = DBTestHelper::createProduct($this->em, $subcategory, 1);
        $this->productType =
            DBTestHelper::createProductType($this->em, $this->product, TestHelper::getRandomString(), 1)
        ;
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        $this->em->close();
        $this->em = null;
    }
}
