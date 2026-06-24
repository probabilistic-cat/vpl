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

    public function testRequiredProperties(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->clear();
        $categoryProperty = $this->em->getRepository(CategoryProperty::class)->find($this->categoryProperty->id);
        $this->assertSame($this->category->id, $categoryProperty->category->id);
        $this->assertSame($this->property->id, $categoryProperty->property->id);
        $this->assertSame($this->categoryProperty->seq, $categoryProperty->seq);
        $this->assertSame(0, $categoryProperty->layer);
        $this->assertSame(true, $categoryProperty->active);
        $this->assertTrue($categoryProperty->created->getTimestamp() <= $beforeModify);
        $this->assertNull($categoryProperty->modified);
    }

    public function testUpdate(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->clear();
        $categoryProperty = $this->em->getRepository(CategoryProperty::class)->find($this->categoryProperty->id);

        $layer = 1;
        $active = false;
        $created = $categoryProperty->created;

        $categoryProperty->layer = $layer;
        $categoryProperty->active = $active;
        $this->em->persist($categoryProperty);
        $this->em->flush();

        $afterModify = new \DateTime()->getTimestamp();

        $this->em->clear();
        $categoryProperty2 = $this->em->getRepository(CategoryProperty::class)->find($this->categoryProperty->id);
        $this->assertSame($layer, $categoryProperty2->layer);
        $this->assertSame($active, $categoryProperty2->active);
        $this->assertSame($created->getTimestamp(), $categoryProperty2->created->getTimestamp());
        $this->assertNotNull($categoryProperty2->modified);
        $this->assertTrue($beforeModify <= $categoryProperty2->modified->getTimestamp());
        $this->assertTrue($categoryProperty2->modified->getTimestamp() <= $afterModify);
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
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteProperty($this->em, $this->property->id);
        $this->em->close();
        $this->em = null;
    }
}
