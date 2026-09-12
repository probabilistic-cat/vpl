<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\Property;
use App\Entity\Subcategory;
use App\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\File\File;

class ProductTest extends IntegrationTestCase
{
    private const string CHAMBERS_NAME_DEFAULT = 'Kammern (Rahmen)';

    private string $name;
    private int $seq;

    private Category $category;
    private Subcategory $subcategory;
    private Product $product;
    private Manufacturer $manufacturer;
    private Property $property;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->product);
        $this->assertSame($this->subcategory->id, $this->product->subcategory->id);
        $this->assertSame($this->name, $this->product->name);
        $this->assertSame($this->seq, $this->product->seq);
        $this->assertSame(self::CHAMBERS_NAME_DEFAULT, $this->product->chambersName);
        $this->assertTrue($this->product->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->product->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->product);

        $description = $this->fixtureService->getRandomString();
        $descriptionFull = $this->fixtureService->getRandomString();
        $seals = $this->fixtureService->getRandomString(2);
        $chambers = $this->fixtureService->getRandomString(3);
        $chambersName = $this->fixtureService->getRandomString();
        $imgFile = $this->fixtureService->getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $this->product->created;

        $this->product->description = $description;
        $this->product->descriptionFull = $descriptionFull;
        $this->product->seals = $seals;
        $this->product->chambers = $chambers;
        $this->product->chambersName = $chambersName;
        $this->product->imgFile = $imgFile;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->product);
        $this->assertSame($description, $this->product->description);
        $this->assertSame($descriptionFull, $this->product->descriptionFull);
        $this->assertSame($seals, $this->product->seals);
        $this->assertSame($chambers, $this->product->chambers);
        $this->assertSame($chambersName, $this->product->chambersName);
        $img = $this->product->img;
        $this->assertNotNull($img);
        $imgFullPath = $this->imageStorage->getAbsolutePath($img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->product->created->getTimestamp());
        $modified = $this->product->modified;
        $this->assertNotNull($modified);
        $this->assertTrue($beforeUpdateTs <= $modified->getTimestamp());
        $this->assertTrue($modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->refresh($this->category);
        $this->em->refresh($this->product);
        $this->em->refresh($this->manufacturer);
        $this->em->refresh($this->property);

        $this->assertCount(0, $this->product->productTypes);
        $productType = $this->dbService->createProductType($this->em, $this->product, $this->fixtureService->getRandomString(), 1);
        $this->product->addProductType($productType);
        $this->assertCount(1, $this->product->productTypes);
        $this->product->removeProductType($productType);
        $this->assertCount(0, $this->product->productTypes);

        $this->assertCount(0, $this->product->productProperties);
        $categoryProperty = $this->dbService->createCategoryProperty($this->em, $this->category, $this->property, 1);
        $productProperty = $this->dbService->createProductProperty($this->em, $this->product, $categoryProperty, 1);
        $this->product->addProductProperty($productProperty);
        $this->assertCount(1, $this->product->productProperties);
        $this->product->removeProductProperty($productProperty);
        $this->assertCount(0, $this->product->productProperties);

        $this->assertCount(0, $this->product->productInfoMiddles);
        $productInfoMiddle = $this->dbService->createProductInfoMiddle($this->em, $this->product, 1);
        $this->product->addProductInfoMiddle($productInfoMiddle);
        $this->assertCount(1, $this->product->productInfoMiddles);
        $this->product->removeProductInfoMiddle($productInfoMiddle);
        $this->assertCount(0, $this->product->productInfoMiddles);

        $this->assertCount(0, $this->product->productInfoBottoms);
        $productInfoBottom = $this->dbService->createProductInfoBottom($this->em, $this->product, $this->fixtureService->getRandomString(), 1);
        $this->product->addProductInfoBottom($productInfoBottom);
        $this->assertCount(1, $this->product->productInfoBottoms);
        $this->product->removeProductInfoBottom($productInfoBottom);
        $this->assertCount(0, $this->product->productInfoBottoms);

        $this->assertCount(0, $this->product->productManufacturers);
        $productManufacturer = $this->dbService->createProductManufacturer($this->em, $this->product, $this->manufacturer, 1);
        $this->product->addProductManufacturer($productManufacturer);
        $this->assertCount(1, $this->product->productManufacturers);
        $this->product->removeProductManufacturer($productManufacturer);
        $this->assertCount(0, $this->product->productManufacturers);
    }

    protected function createObjects(): void {
        $this->name = $this->fixtureService->getRandomString();
        $this->seq = 1;

        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $this->subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
        $this->product = $this->dbService->createProduct($this->em, $this->subcategory, $this->name, $this->seq);
        $this->manufacturer = $this->dbService->createManufacturer($this->em, $this->fixtureService->getRandomString());
        $this->property = $this->dbService->createProperty($this->em, $this->fixtureService->getRandomString());
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
        $this->dbService->deleteManufacturer($this->em, $this->manufacturer->id);
        $this->dbService->deleteProperty($this->em, $this->property->id);
    }
}
