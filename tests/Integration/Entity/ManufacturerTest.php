<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Manufacturer;
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

        $imgFile = $this->fixtureService->getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $this->manufacturer->created;

        $this->manufacturer->imgFile = $imgFile;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->manufacturer);
        $img = $this->manufacturer->img;
        $this->assertNotNull($img);
        $imgFullPath = $this->imageStorage->getAbsolutePath($img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->manufacturer->created->getTimestamp());
        $modified = $this->manufacturer->modified;
        $this->assertNotNull($modified);
        $this->assertTrue($beforeUpdateTs <= $modified->getTimestamp());
        $this->assertTrue($modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->refresh($this->category);
        $this->em->refresh($this->manufacturer);

        $this->assertCount(0, $this->manufacturer->productManufacturers);
        $subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
        $product = $this->dbService->createProduct($this->em, $subcategory, $this->fixtureService->getRandomString(), 1);
        $productManufacturer = $this->dbService->createProductManufacturer($this->em, $product, $this->manufacturer, 1);
        $this->manufacturer->addProductManufacturer($productManufacturer);
        $this->assertCount(1, $this->manufacturer->productManufacturers);
        $this->manufacturer->removeProductManufacturer($productManufacturer);
        $this->assertCount(0, $this->manufacturer->productManufacturers);
    }

    protected function createObjects(): void {
        $this->name = $this->fixtureService->getRandomString();

        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $this->manufacturer = $this->dbService->createManufacturer($this->em, $this->name);
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
        $this->dbService->deleteManufacturer($this->em, $this->manufacturer->id);
    }
}
