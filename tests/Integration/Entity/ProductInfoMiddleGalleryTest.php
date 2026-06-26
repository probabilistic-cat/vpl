<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\ProductInfoMiddle;
use App\Entity\ProductInfoMiddleGallery;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;

class ProductInfoMiddleGalleryTest extends KernelTestCase
{
    private EntityManagerInterface $em;

    private string $imgFileContent;
    private int $seq;

    private Category $category;
    private ProductInfoMiddle $productInfoMiddle;
    private ProductInfoMiddleGallery $productInfoMiddleGallery;

    public function testRequiredProperties(): void {
        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productInfoMiddleGallery);
        $this->assertSame($this->productInfoMiddle->id, $this->productInfoMiddleGallery->productInfoMiddle->id);
        $this->assertSame($this->seq, $this->productInfoMiddleGallery->seq);
        $imgFullPath = FileHelper::DIR_PUBLIC . $this->productInfoMiddleGallery->img;
        $this->assertFileExists($imgFullPath);
        $this->assertSame($this->imgFileContent, new File($imgFullPath)->getContent());
        $this->assertTrue($this->productInfoMiddleGallery->created->getTimestamp() <= $afterUpdateTs);
        $this->assertTrue($this->productInfoMiddleGallery->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $imgFile = TestHelper::getImgFile();
        $this->imgFileContent = $imgFile->getContent();
        $this->seq = 1;

        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
        $product = DBTestHelper::createProduct($this->em, $subcategory, TestHelper::getRandomString(), 1);
        $this->productInfoMiddle = DBTestHelper::createProductInfoMiddle($this->em, $product, 1);
        $this->productInfoMiddleGallery =
            DBTestHelper::createProductInfoMiddleGallery($this->em, $this->productInfoMiddle, $imgFile, $this->seq)
        ;
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        $this->em->close();
    }
}
