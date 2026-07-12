<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\CategoryProperty;
use App\Entity\Product;
use App\Entity\ProductProperty;
use App\Entity\Property;
use App\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\File\File;

class ProductPropertyTest extends IntegrationTestCase
{
    private int $seq;

    private Category $category;
    private Product $product;
    private Property $property;
    private CategoryProperty $categoryProperty;
    private ProductProperty $productProperty;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productProperty);
        $this->assertSame($this->product->id, $this->productProperty->product->id);
        $this->assertSame($this->categoryProperty->id, $this->productProperty->categoryProperty->id);
        $this->assertSame($this->seq, $this->productProperty->seq);
        $this->assertTrue($this->productProperty->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->productProperty->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productProperty);

        $name = $this->fixtureService->getRandomString();
        $imgFile = $this->fixtureService->getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $this->productProperty->created;

        $this->em->refresh($this->property);
        $propertySet = $this->dbService->createPropertySet($this->em, $this->property, $this->fixtureService->getRandomString());
        $this->productProperty->propertySet = $propertySet;
        $this->productProperty->name = $name;
        $this->productProperty->imgFile = $imgFile;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->productProperty);
        $this->assertSame($propertySet->id, $this->productProperty->propertySet->id);
        $this->assertSame($name, $this->productProperty->name);
        $imgFullPath = $this->imageStorage->getAbsolutePath($this->productProperty->img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->productProperty->created->getTimestamp());
        $this->assertNotNull($this->productProperty->modified);
        $this->assertTrue($beforeUpdateTs <= $this->productProperty->modified->getTimestamp());
        $this->assertTrue($this->productProperty->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function createObjects(): void {
        $this->seq = 1;

        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
        $this->product = $this->dbService->createProduct($this->em, $subcategory, $this->fixtureService->getRandomString(), 1);
        $this->property = $this->dbService->createProperty($this->em, $this->fixtureService->getRandomString());
        $this->categoryProperty = $this->dbService->createCategoryProperty($this->em, $this->category, $this->property, 1);
        $this->productProperty =
            $this->dbService->createProductProperty($this->em, $this->product, $this->categoryProperty, $this->seq)
        ;
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
        $this->dbService->deleteProperty($this->em, $this->property->id);
    }
}
