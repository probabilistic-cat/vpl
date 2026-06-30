<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use App\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\File\File;

class ManufacturerTest extends IntegrationTestCase
{
    private string $name;

    private Category $category;
    private Manufacturer $manufacturer;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->manufacturer);
        $this->assertSame($this->name, $this->manufacturer->name);
        $this->assertTrue($this->manufacturer->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->manufacturer->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->manufacturer);

        $imgFile = TestHelper::getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $this->manufacturer->created;

        $this->manufacturer->imgFile = $imgFile;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->manufacturer);
        $imgFullPath = FileHelper::DIR_PUBLIC . $this->manufacturer->img;
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->manufacturer->created->getTimestamp());
        $this->assertNotNull($this->manufacturer->modified);
        $this->assertTrue($beforeUpdateTs <= $this->manufacturer->modified->getTimestamp());
        $this->assertTrue($this->manufacturer->modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->refresh($this->category);
        $this->em->refresh($this->manufacturer);

        $this->assertSame(0, $this->manufacturer->productManufacturers->count());
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
        $product = DBTestHelper::createProduct($this->em, $subcategory, TestHelper::getRandomString(), 1);
        $productManufacturer = DBTestHelper::createProductManufacturer($this->em, $product, $this->manufacturer, 1);
        $this->manufacturer->addProductManufacturer($productManufacturer);
        $this->assertSame(1, $this->manufacturer->productManufacturers->count());
        $this->manufacturer->removeProductManufacturer($productManufacturer);
        $this->assertSame(0, $this->manufacturer->productManufacturers->count());
    }

    protected function createObjects(): void {
        $this->name = TestHelper::getRandomString();

        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $this->manufacturer = DBTestHelper::createManufacturer($this->em, $this->name);
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer->id);
    }
}
