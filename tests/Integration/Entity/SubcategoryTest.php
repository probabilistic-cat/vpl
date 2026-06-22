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

class SubcategoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;
    private Subcategory $subcategory;

    public function testSubcategory(): void {
        $beforeModify = new \DateTime()->getTimestamp();
        $this->em->clear();
        $subcategory = $this->em->getRepository(Subcategory::class)->find($this->subcategory->getId());
        $this->assertSame($this->category->getId(), $subcategory->category->getId());
        $this->assertSame($this->subcategory->name, $subcategory->name);
        $this->assertTrue($subcategory->getCreated()->getTimestamp() <= $beforeModify);
        $this->assertNull($subcategory->getModified());

        $subcategory->description = TestHelper::getRandomString();
        $subcategory->imgFile = TestHelper::getImgFile();
        $this->em->persist($subcategory);
        $this->em->flush();

        $afterModify = new \DateTime()->getTimestamp();
        $this->em->clear();
        $subcategory2 = $this->em->getRepository(Subcategory::class)->find($this->subcategory->getId());
        $this->assertSame($subcategory->description, $subcategory2->description);
        $this->assertSame($subcategory->img, $subcategory2->img);
        $this->assertFileExists(FileHelper::DIR_PUBLIC . $subcategory2->img);
        $this->assertEquals($subcategory->getCreated(), $subcategory2->getCreated());
        $this->assertNotNull($subcategory2->getModified());
        $this->assertTrue($beforeModify <= $subcategory2->getModified()->getTimestamp());
        $this->assertTrue($subcategory2->getModified()->getTimestamp() <= $afterModify);
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
        DBTestHelper::deleteCategory($this->em, $this->category->getId());
        $this->em->close();
        $this->em = null;
    }
}
