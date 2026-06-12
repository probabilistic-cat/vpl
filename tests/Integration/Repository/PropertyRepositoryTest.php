<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Property;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PropertyRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;

    public function testCreateCategoryQueryBuilder(): void {
        $this->em->clear();
        $propertyRepository = $this->em->getRepository(Property::class);
        $properties = $propertyRepository->createPropertyWithoutDescQueryBuilder()->getQuery()->getResult();
        $propertyBeschreibung = $propertyRepository->findOneBy(['name' => Property::NAME_BESCHREIBUNG]);
        $matchedProperties = array_filter(
            $properties,
            static fn($property): bool => $property->getId() === $propertyBeschreibung->getId())
        ;
        $this->assertCount(0, $matchedProperties);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::$container->get(EntityManagerInterface::class);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->close();
        $this->em = null;
    }
}
