<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
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

        $description = TestHelper::getRandomString();
        $color = TestHelper::getRandomColor();
        $imgFile = TestHelper::getImgFile();
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
        $imgFullPath = $this->imageStorage->getAbsolutePath($this->category->img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->category->created->getTimestamp());
        $this->assertNotNull($this->category->modified);
        $this->assertTrue($beforeUpdateTs <= $this->category->modified->getTimestamp());
        $this->assertTrue($this->category->modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->refresh($this->category);

        $this->assertSame(0, $this->category->subcategories->count());
        $subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
        $this->category->addSubcategory($subcategory);
        $this->assertSame(1, $this->category->subcategories->count());
        $this->category->removeSubcategory($subcategory);
        $this->assertSame(0, $this->category->subcategories->count());

        $this->assertSame(0, $this->category->categoryProperties->count());
        $property = DBTestHelper::createProperty($this->em, TestHelper::getRandomString());
        $categoryProperty = DBTestHelper::createCategoryProperty($this->em, $this->category, $property, 1);
        $this->category->addCategoryProperty($categoryProperty);
        $this->assertSame(1, $this->category->categoryProperties->count());
        $this->category->removeCategoryProperty($categoryProperty);
        $this->assertSame(0, $this->category->categoryProperties->count());
    }

    protected function createObjects(): void {
        $this->name = TestHelper::getRandomString();

        $this->category = DBTestHelper::createCategory($this->em, $this->name);
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteCategory($this->em, $this->category->id);
    }
}
