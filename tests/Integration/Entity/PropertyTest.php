<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\Property;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PropertyTest extends KernelTestCase
{
    private EntityManagerInterface $em;

    private string $name;

    private Category $category;
    private Property $property;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->property);
        $this->assertSame($this->name, $this->property->name);
        $this->assertTrue($this->property->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->property->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->property);

        $name = TestHelper::getRandomString();
        $created = $this->property->created;

        $this->property->name = $name;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->property);
        $this->assertSame($name, $this->property->name);
        $this->assertSame($created->getTimestamp(), $this->property->created->getTimestamp());
        $this->assertNotNull($this->property->modified);
        $this->assertTrue($beforeUpdateTs <= $this->property->modified->getTimestamp());
        $this->assertTrue($this->property->modified->getTimestamp() <= $afterUpdateTs);
    }

    public function testCollections(): void {
        $this->em->refresh($this->category);
        $this->em->refresh($this->property);

        $this->assertSame(0, $this->property->categoryProperties->count());
        $categoryProperty = DBTestHelper::createCategoryProperty($this->em, $this->category, $this->property, 1);
        $this->property->addCategoryProperty($categoryProperty);
        $this->assertSame(1, $this->property->categoryProperties->count());
        $this->property->removeCategoryProperty($categoryProperty);
        $this->assertSame(0, $this->property->categoryProperties->count());

        $this->assertSame(0, $this->property->propertySets->count());
        $propertySet = DBTestHelper::createPropertySet($this->em, $this->property, TestHelper::getRandomString());
        $this->property->addPropertySet($propertySet);
        $this->assertSame(1, $this->property->propertySets->count());
        $this->property->removePropertySet($propertySet);
        $this->assertSame(0, $this->property->propertySets->count());
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $this->name = TestHelper::getRandomString();

        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $this->property = DBTestHelper::createProperty($this->em, $this->name);
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteProperty($this->em, $this->property->id);
        $this->em->close();
    }
}
