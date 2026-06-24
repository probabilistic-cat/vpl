<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;

    public function testCategory(): void {
        $beforeModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $category = $this->em->getRepository(Category::class)->find($this->category->id);
        $this->assertSame($this->category->name, $category->name);
        $this->assertSame('#c9eeff', $category->color);
        $this->assertTrue($category->created->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($category->modified);

        $category->description = TestHelper::getRandomString();
        $category->color = TestHelper::getRandomColor();
        $category->imgFile = TestHelper::getImgFile();
        $this->em->persist($category);
        $this->em->flush();

        $afterModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $category2 = $this->em->getRepository(Category::class)->find($this->category->id);
        $this->assertSame($category->description, $category2->description);
        $this->assertSame($category->color, $category2->color);
        $this->assertSame($category->img, $category2->img);
        $this->assertFileExists(FileHelper::DIR_PUBLIC . $category2->img);
        $this->assertEquals($category->created, $category2->created);
        $this->assertNotNull($category2->modified);
        $this->assertTrue($beforeModifyTs <= $category2->modified->getTimestamp());
        $this->assertTrue($category2->modified->getTimestamp() <= $afterModifyTs);
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
