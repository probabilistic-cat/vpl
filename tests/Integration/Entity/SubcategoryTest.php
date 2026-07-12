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
        $imgFullPath = $this->imageStorage->getAbsolutePath($this->subcategory->img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->subcategory->created->getTimestamp());
        $this->assertNotNull($this->subcategory->modified);
        $this->assertTrue($beforeModify <= $this->subcategory->modified->getTimestamp());
        $this->assertTrue($this->subcategory->modified->getTimestamp() <= $afterModify);
    }

    public function testCollections(): void {
        $this->em->refresh($this->subcategory);

        $this->assertSame(0, $this->subcategory->products->count());
        $product = $this->dbService->createProduct($this->em, $this->subcategory, $this->fixtureService->getRandomString(), 1);
        $this->subcategory->addProduct($product);
        $this->assertSame(1, $this->subcategory->products->count());
        $this->subcategory->removeProduct($product);
        $this->assertSame(0, $this->subcategory->products->count());
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
