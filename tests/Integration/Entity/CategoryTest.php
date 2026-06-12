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
        $beforeModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $category = $this->em->getRepository(Category::class)->find($this->category->getId());
        $this->assertSame($this->category->getName(), $category->getName());
        $this->assertSame('#c9eeff', $category->getColor());
        $this->assertTrue($category->getCreated()->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($category->getModified());

        $category->setDescription(TestHelper::getRandomString());
        $category->setColor(TestHelper::getRandomColor());
        $category->setImgFile(TestHelper::getImgFile());
        $this->em->persist($category);
        $this->em->flush();

        $afterModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $category2 = $this->em->getRepository(Category::class)->find($this->category->getId());
        $this->assertSame($category->getDescription(), $category2->getDescription());
        $this->assertSame($category->getColor(), $category2->getColor());
        $this->assertSame($category->getImg(), $category2->getImg());
        $this->assertFileExists(FileHelper::DIR_PUBLIC . $category2->getImg());
        $this->assertEquals($category->getCreated(), $category2->getCreated());
        $this->assertNotNull($category2->getModified());
        $this->assertTrue($beforeModifyTs <= $category2->getModified()->getTimestamp());
        $this->assertTrue($category2->getModified()->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::$container->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->getId());
        $this->em->close();
        $this->em = null;
    }
}
