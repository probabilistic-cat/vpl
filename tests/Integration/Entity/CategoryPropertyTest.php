<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\CategoryProperty;
use App\Entity\Property;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryPropertyTest extends KernelTestCase
{
    private EntityManagerInterface $em;

    private int $seq;

    private Category $category;
    private Property $property;
    private CategoryProperty $categoryProperty;

    public function testRequiredProperties(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->categoryProperty);
        $this->assertSame($this->category->id, $this->categoryProperty->category->id);
        $this->assertSame($this->property->id, $this->categoryProperty->property->id);
        $this->assertSame($this->seq, $this->categoryProperty->seq);
        $this->assertSame(0, $this->categoryProperty->layer);
        $this->assertSame(true, $this->categoryProperty->active);
        $this->assertTrue($this->categoryProperty->created->getTimestamp() <= $beforeModify);
        $this->assertNull($this->categoryProperty->modified);
    }

    public function testUpdate(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->categoryProperty);

        $layer = 1;
        $active = false;
        $created = $this->categoryProperty->created;

        $this->categoryProperty->layer = $layer;
        $this->categoryProperty->active = $active;
        $this->em->flush();

        $afterModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->categoryProperty);
        $this->assertSame($layer, $this->categoryProperty->layer);
        $this->assertSame($active, $this->categoryProperty->active);
        $this->assertSame($created->getTimestamp(), $this->categoryProperty->created->getTimestamp());
        $this->assertNotNull($this->categoryProperty->modified);
        $this->assertTrue($beforeModify <= $this->categoryProperty->modified->getTimestamp());
        $this->assertTrue($this->categoryProperty->modified->getTimestamp() <= $afterModify);
    }

    public function testCollections(): void {
        $this->em->refresh($this->categoryProperty);

        $this->assertSame(0, $this->categoryProperty->productProperties->count());
        $category = $this->em->getRepository(Category::class)->find($this->category);
        $subcategory = DBTestHelper::createSubcategory($this->em, $category, TestHelper::getRandomString());
        $product = DBTestHelper::createProduct($this->em, $subcategory, TestHelper::getRandomString(), 1);
        $productProperty = DBTestHelper::createProductProperty($this->em, $product, $this->categoryProperty, 1);
        $this->categoryProperty->addProductProperty($productProperty);
        $this->assertSame(1, $this->categoryProperty->productProperties->count());
        $this->categoryProperty->removeProductProperty($productProperty);
        $this->assertSame(0, $this->categoryProperty->productProperties->count());
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $this->seq = 1;

        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $this->property = DBTestHelper::createProperty($this->em, TestHelper::getRandomString());
        $this->categoryProperty =
            DBTestHelper::createCategoryProperty($this->em, $this->category, $this->property, $this->seq)
        ;
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteProperty($this->em, $this->property->id);
        $this->em->close();
    }
}
