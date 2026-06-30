<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Property;
use App\Tests\Integration\IntegrationTestCase;

class PropertyRepositoryTest extends IntegrationTestCase
{
    public function testCreateCategoryQueryBuilder(): void {
        $this->em->clear();
        $propertyRepository = $this->em->getRepository(Property::class);
        $properties = $propertyRepository->getQBWithoutDesc()->getQuery()->getResult();
        $propertyBeschreibung = $propertyRepository->findOneBy(['name' => Property::NAME_BESCHREIBUNG]);
        $matchedProperties = array_filter(
            $properties,
            static fn ($property): bool => $property->id === $propertyBeschreibung->id,
        );
        $this->assertCount(0, $matchedProperties);
    }
}
