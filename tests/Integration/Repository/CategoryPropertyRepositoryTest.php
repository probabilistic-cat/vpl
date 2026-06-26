<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Category;
use App\Entity\CategoryProperty;
use App\Entity\Property;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryPropertyRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private Category $category;
    private Property $property;
    private CategoryProperty $categoryProperty;

    public function testCreateCategoryQueryBuilder(): void {
        $this->em->clear();
        $categoryPropertyRepository = $this->em->getRepository(CategoryProperty::class);
        $categoryProperties = $categoryPropertyRepository
            ->getQBWithoutDesc($this->category)
            ->getQuery()
            ->getResult()
        ;
        $this->assertCount(1, $categoryProperties);
        $categoryProperty = $categoryProperties[0];
        $this->assertInstanceOf(CategoryProperty::class, $categoryProperty);
        $this->assertSame($this->categoryProperty->id, $categoryProperty->id);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $propertyBeschreibung = $this->em->getRepository(Property::class)
            ->findOneBy(['name' => Property::NAME_BESCHREIBUNG])
        ;
        DBTestHelper::createCategoryProperty($this->em, $this->category, $propertyBeschreibung, 1);
        $this->property = DBTestHelper::createProperty($this->em, TestHelper::getRandomString());
        $this->categoryProperty = DBTestHelper::createCategoryProperty($this->em, $this->category, $this->property, 2);
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteProperty($this->em, $this->property->id);
        $this->em->close();
    }
}
