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
        /** @var array<Property> $properties */
        $properties = $propertyRepository->getQBWithoutDesc()->getQuery()->getResult();
        /** @var Property $propertyBeschreibung */
        $propertyBeschreibung = $propertyRepository->findOneBy(['name' => Property::NAME_BESCHREIBUNG]);
        $matchedProperties = array_filter(
            $properties,
            static fn (Property $property): bool => $property->id === $propertyBeschreibung->id,
        );
        $this->assertCount(0, $matchedProperties);
    }
}
