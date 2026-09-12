<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Subcategory;
use App\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\File\File;

class SubcategoryTest extends IntegrationTestCase
{
    private string $name;

    private Category $category;
    private Subcategory $subcategory;

    public function testRequiredProperties(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->subcategory);
        $this->assertSame($this->category->id, $this->subcategory->category->id);
        $this->assertSame($this->name, $this->subcategory->name);
        $this->assertTrue($this->subcategory->created->getTimestamp() <= $beforeModify);
        $this->assertNull($this->subcategory->modified);
    }

    public function testUpdate(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->subcategory);

        $description = $this->fixtureService->getRandomString();
        $imgFile = $this->fixtureService->getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $this->subcategory->created;

        $this->subcategory->description = $description;
        $this->subcategory->imgFile = $imgFile;
        $this->em->flush();

        $afterModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->subcategory);
        $this->assertSame($description, $this->subcategory->description);
        $img = $this->subcategory->img;
        $this->assertNotNull($img);
        $imgFullPath = $this->imageStorage->getAbsolutePath($img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->subcategory->created->getTimestamp());
        $modified = $this->subcategory->modified;
        $this->assertNotNull($modified);
        $this->assertTrue($beforeModify <= $modified->getTimestamp());
        $this->assertTrue($modified->getTimestamp() <= $afterModify);
    }

    public function testCollections(): void {
        $this->em->refresh($this->subcategory);

        $this->assertCount(0, $this->subcategory->products);
        $product = $this->dbService->createProduct($this->em, $this->subcategory, $this->fixtureService->getRandomString(), 1);
        $this->subcategory->addProduct($product);
        $this->assertCount(1, $this->subcategory->products);
        $this->subcategory->removeProduct($product);
        $this->assertCount(0, $this->subcategory->products);
    }

    protected function createObjects(): void {
        $this->name = $this->fixtureService->getRandomString();

        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $this->subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->name);
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
    }
}
