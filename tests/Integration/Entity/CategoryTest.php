<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;

class CategoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $category = $this->em->getRepository(Category::class)->find($this->category->id);
        $this->assertSame($this->category->name, $category->name);
        $this->assertSame('#c9eeff', $category->color);
        $this->assertTrue($category->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($category->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $category = $this->em->getRepository(Category::class)->find($this->category);

        $description = TestHelper::getRandomString();
        $color = TestHelper::getRandomColor();
        $imgFile = TestHelper::getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $category->created;

        $category->description = $description;
        $category->color = $color;
        $category->imgFile = $imgFile;
        $this->em->persist($category);
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $category2 = $this->em->getRepository(Category::class)->find($this->category->id);
        $imgFullPath = FileHelper::DIR_PUBLIC . $category2->img;
        $this->assertSame($description, $category2->description);
        $this->assertSame($color, $category2->color);
        $this->assertSame($category->img, $category2->img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $category2->created->getTimestamp());
        $this->assertNotNull($category2->modified);
        $this->assertTrue($beforeUpdateTs <= $category2->modified->getTimestamp());
        $this->assertTrue($category2->modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->clear();
        $category = $this->em->getRepository(Category::class)->find($this->category);

        $this->assertSame(0, $category->subcategories->count());
        $subcategory = DBTestHelper::createSubcategory($this->em, $category);
        $category->addSubcategory($subcategory);
        $this->assertSame(1, $category->subcategories->count());
        $category->removeSubcategory($subcategory);
        $this->assertSame(0, $category->subcategories->count());

        $this->assertSame(0, $category->categoryProperties->count());
        $property = DBTestHelper::createProperty($this->em);
        $categoryProperty = DBTestHelper::createCategoryProperty($this->em, $category, $property, 1);
        $category->addCategoryProperty($categoryProperty);
        $this->assertSame(1, $category->categoryProperties->count());
        $category->removeCategoryProperty($categoryProperty);
        $this->assertSame(0, $category->categoryProperties->count());
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        $this->em->close();
        $this->em = null;
    }
}
