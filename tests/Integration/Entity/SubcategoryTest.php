<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Subcategory;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;

class SubcategoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;
    private Subcategory $subcategory;

    public function testRequiredProperties(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->clear();
        $subcategory = $this->em->getRepository(Subcategory::class)->find($this->subcategory->id);
        $this->assertSame($this->category->id, $subcategory->category->id);
        $this->assertSame($this->subcategory->name, $subcategory->name);
        $this->assertTrue($subcategory->created->getTimestamp() <= $beforeModify);
        $this->assertNull($subcategory->modified);
    }

    public function testUpdate(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->clear();
        $subcategory = $this->em->getRepository(Subcategory::class)->find($this->subcategory);

        $description = TestHelper::getRandomString();
        $imgFile = TestHelper::getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $subcategory->created;

        $subcategory->description = $description;
        $subcategory->imgFile = $imgFile;
        $this->em->persist($subcategory);
        $this->em->flush();

        $afterModify = new \DateTime()->getTimestamp();

        $this->em->clear();
        $subcategory2 = $this->em->getRepository(Subcategory::class)->find($this->subcategory->id);
        $imgFullPath = FileHelper::DIR_PUBLIC . $subcategory2->img;
        $this->assertSame($description, $subcategory2->description);
        $this->assertSame($subcategory->img, $subcategory2->img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $subcategory2->created->getTimestamp());
        $this->assertNotNull($subcategory2->modified);
        $this->assertTrue($beforeModify <= $subcategory2->modified->getTimestamp());
        $this->assertTrue($subcategory2->modified->getTimestamp() <= $afterModify);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $this->subcategory = DBTestHelper::createSubcategory($this->em, $this->category);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        $this->em->close();
        $this->em = null;
    }
}
