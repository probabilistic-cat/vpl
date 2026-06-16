<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\CategoryProperty;
use App\Entity\Property;
use App\Tests\Helper\DBTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryPropertyTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;
    private Property $property;
    private CategoryProperty $categoryProperty;

    public function testCategoryProperty(): void {
        $beforeModify = new \DateTime()->getTimestamp();
        $this->em->clear();
        $categoryProperty = $this->em->getRepository(CategoryProperty::class)->find($this->categoryProperty->getId());
        $this->assertSame($this->category->getId(), $categoryProperty->getCategory()->getId());
        $this->assertSame($this->property->getId(), $categoryProperty->getProperty()->getId());
        $this->assertSame($this->categoryProperty->getSeq(), $categoryProperty->getSeq());
        $this->assertSame(0, $categoryProperty->getLayer());
        $this->assertSame(true, $categoryProperty->getActive());
        $this->assertTrue($categoryProperty->getCreated()->getTimestamp() <= $beforeModify);
        $this->assertNull($categoryProperty->getModified());

        $categoryProperty->setLayer(1);
        $categoryProperty->setActive(false);
        $this->em->persist($categoryProperty);
        $this->em->flush();

        $afterModify = new \DateTime()->getTimestamp();
        $this->em->clear();
        $categoryProperty2 = $this->em->getRepository(CategoryProperty::class)->find($this->categoryProperty->getId());
        $this->assertSame($categoryProperty->getLayer(), $categoryProperty2->getLayer());
        $this->assertSame($categoryProperty->getActive(), $categoryProperty2->getActive());
        $this->assertEquals($categoryProperty->getCreated(), $categoryProperty2->getCreated());
        $this->assertNotNull($categoryProperty2->getModified());
        $this->assertTrue($beforeModify <= $categoryProperty2->getModified()->getTimestamp());
        $this->assertTrue($categoryProperty2->getModified()->getTimestamp() <= $afterModify);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $this->property = DBTestHelper::createProperty($this->em);
        $this->categoryProperty = DBTestHelper::createCategoryProperty($this->em, $this->category, $this->property, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->getId());
        DBTestHelper::deleteProperty($this->em, $this->property->getId());
        $this->em->close();
        $this->em = null;
    }
}
