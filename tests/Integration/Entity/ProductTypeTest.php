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
use Symfony\Component\HttpFoundation\File\File;

class ProductTypeTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;
    private Product $product;
    private ProductType $productType;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $productType = $this->em->getRepository(ProductType::class)->find($this->productType->id);
        $this->assertSame($this->product->id, $productType->product->id);
        $this->assertSame($this->productType->text, $productType->text);
        $this->assertSame($this->productType->seq, $productType->seq);
        $this->assertTrue($productType->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($productType->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $productType = $this->em->getRepository(ProductType::class)->find($this->productType->id);

        $imgFile = TestHelper::getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $productType->created;

        $productType->imgFile = $imgFile;
        $this->em->persist($productType);
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $productType2 = $this->em->getRepository(ProductType::class)->find($this->productType->id);
        $imgFullPath = FileHelper::DIR_PUBLIC . $productType2->img;
        $this->assertSame($productType->img, $productType2->img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $productType2->created->getTimestamp());
        $this->assertNotNull($productType2->modified);
        $this->assertTrue($beforeUpdateTs <= $productType2->modified->getTimestamp());
        $this->assertTrue($productType2->modified->getTimestamp() <= $afterUpdateTs);
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
