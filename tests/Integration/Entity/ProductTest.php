<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Subcategory;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;
    private Subcategory $subcategory;
    private Product $product;

    public function testProduct(): void {
        $beforeModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $product = $this->em->getRepository(Product::class)->find($this->product->getId());
        $this->assertSame($this->subcategory->getId(), $product->getSubcategory()->getId());
        $this->assertSame($this->product->getName(), $product->getName());
        $this->assertSame($this->product->getSeq(), $product->getSeq());
        $this->assertSame('Kammern (Rahmen)', $product->getChambersName());
        $this->assertTrue($product->getCreated()->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($product->getModified());

        $product->setDescription(TestHelper::getRandomString());
        $product->setDescriptionFull(TestHelper::getRandomString());
        $product->setSeals(TestHelper::getRandomString(2));
        $product->setChambers(TestHelper::getRandomString(3));
        $product->setChambersName(TestHelper::getRandomString());
        $product->setImgFile(TestHelper::getImgFile());
        $this->em->persist($product);
        $this->em->flush();

        $afterModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $product2 = $this->em->getRepository(Product::class)->find($this->product->getId());
        $this->assertSame($product->getDescription(), $product2->getDescription());
        $this->assertSame($product->getDescriptionFull(), $product2->getDescriptionFull());
        $this->assertSame($product->getSeals(), $product2->getSeals());
        $this->assertSame($product->getChambers(), $product2->getChambers());
        $this->assertSame($product->getChambersName(), $product2->getChambersName());
        $this->assertSame($product->getImg(), $product2->getImg());
        $this->assertFileExists(FileHelper::DIR_PUBLIC . $product2->getImg());
        $this->assertEquals($product->getCreated(), $product2->getCreated());
        $this->assertNotNull($product2->getModified());
        $this->assertTrue($beforeModifyTs <= $product2->getModified()->getTimestamp());
        $this->assertTrue($product2->getModified()->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $this->subcategory = DBTestHelper::createSubcategory($this->em, $this->category);
        $this->product = DBTestHelper::createProduct($this->em, $this->subcategory, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->getId());
        $this->em->close();
        $this->em = null;
    }
}
