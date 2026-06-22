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
        $this->assertSame($this->subcategory->getId(), $product->subcategory->getId());
        $this->assertSame($this->product->name, $product->name);
        $this->assertSame($this->product->seq, $product->seq);
        $this->assertSame('Kammern (Rahmen)', $product->chambersName);
        $this->assertTrue($product->getCreated()->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($product->getModified());

        $product->description = TestHelper::getRandomString();
        $product->descriptionFull = TestHelper::getRandomString();
        $product->seals = TestHelper::getRandomString(2);
        $product->chambers = TestHelper::getRandomString(3);
        $product->chambersName = TestHelper::getRandomString();
        $product->imgFile = TestHelper::getImgFile();
        $this->em->persist($product);
        $this->em->flush();

        $afterModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $product2 = $this->em->getRepository(Product::class)->find($this->product->getId());
        $this->assertSame($product->description, $product2->description);
        $this->assertSame($product->descriptionFull, $product2->descriptionFull);
        $this->assertSame($product->seals, $product2->seals);
        $this->assertSame($product->chambers, $product2->chambers);
        $this->assertSame($product->chambersName, $product2->chambersName);
        $this->assertSame($product->img, $product2->img);
        $this->assertFileExists(FileHelper::DIR_PUBLIC . $product2->img);
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
