<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\File\File;

class CategoryTest extends IntegrationTestCase
{
    private const string COLOR_DEFAULT = '#c9eeff';

    private string $name;

    private Category $category;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->category);
        $this->assertSame($this->name, $this->category->name);
        $this->assertSame(self::COLOR_DEFAULT, $this->category->color);
        $this->assertTrue($this->category->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->category->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->category);

        $description = $this->fixtureService->getRandomString();
        $color = $this->fixtureService->getRandomColor();
        $imgFile = $this->fixtureService->getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $this->category->created;

        $this->category->description = $description;
        $this->category->color = $color;
        $this->category->imgFile = $imgFile;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->category);
        $this->assertSame($description, $this->category->description);
        $this->assertSame($color, $this->category->color);
        $img = $this->category->img;
        $this->assertNotNull($img);
        $imgFullPath = $this->imageStorage->getAbsolutePath($img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->category->created->getTimestamp());
        $modified = $this->category->modified;
        $this->assertNotNull($modified);
        $this->assertTrue($beforeUpdateTs <= $modified->getTimestamp());
        $this->assertTrue($modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->refresh($this->category);

        $this->assertCount(0, $this->category->subcategories);
        $subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
        $this->category->addSubcategory($subcategory);
        $this->assertCount(1, $this->category->subcategories);
        $this->category->removeSubcategory($subcategory);
        $this->assertCount(0, $this->category->subcategories);

        $this->assertCount(0, $this->category->categoryProperties);
        $property = $this->dbService->createProperty($this->em, $this->fixtureService->getRandomString());
        $categoryProperty = $this->dbService->createCategoryProperty($this->em, $this->category, $property, 1);
        $this->category->addCategoryProperty($categoryProperty);
        $this->assertCount(1, $this->category->categoryProperties);
        $this->category->removeCategoryProperty($categoryProperty);
        $this->assertCount(0, $this->category->categoryProperties);
    }

    protected function createObjects(): void {
        $this->name = $this->fixtureService->getRandomString();

        $this->category = $this->dbService->createCategory($this->em, $this->name);
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
    }
}
