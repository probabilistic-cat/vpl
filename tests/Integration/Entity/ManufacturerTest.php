<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;

class ManufacturerTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;
    private Manufacturer $manufacturer;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $manufacturer = $this->em->getRepository(Manufacturer::class)->find($this->manufacturer->id);
        $this->assertSame($this->manufacturer->name, $manufacturer->name);
        $this->assertTrue($manufacturer->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($manufacturer->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $manufacturer = $this->em->getRepository(Manufacturer::class)->find($this->manufacturer->id);

        $imgFile = TestHelper::getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $manufacturer->created;

        $manufacturer->imgFile = $imgFile;
        $this->em->persist($manufacturer);
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $manufacturer2 = $this->em->getRepository(Manufacturer::class)->find($this->manufacturer->id);
        $imgFullPath = FileHelper::DIR_PUBLIC . $manufacturer2->img;
        $this->assertSame($manufacturer->img, $manufacturer2->img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $manufacturer2->created->getTimestamp());
        $this->assertNotNull($manufacturer2->modified);
        $this->assertTrue($beforeUpdateTs <= $manufacturer2->modified->getTimestamp());
        $this->assertTrue($manufacturer2->modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->clear();
        $category = $this->em->getRepository(Category::class)->find($this->category);
        $manufacturer = $this->em->getRepository(Manufacturer::class)->find($this->manufacturer->id);

        $this->assertSame(0, $manufacturer->productManufacturers->count());
        $subcategory = DBTestHelper::createSubcategory($this->em, $category);
        $product = DBTestHelper::createProduct($this->em, $subcategory, 1);
        $productManufacturer = DBTestHelper::createProductManufacturer($this->em, $product, $manufacturer, 1);
        $manufacturer->addProductManufacturer($productManufacturer);
        $this->assertSame(1, $manufacturer->productManufacturers->count());
        $manufacturer->removeProductManufacturer($productManufacturer);
        $this->assertSame(0, $manufacturer->productManufacturers->count());
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $this->manufacturer = DBTestHelper::createManufacturer($this->em);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer->id);
        $this->em->close();
        $this->em = null;
    }
}
