<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Manufacturer;
use App\Tests\Integration\IntegrationTestCase;

class ManufacturerRepositoryTest extends IntegrationTestCase
{
    private Manufacturer $manufacturer1;
    private Manufacturer $manufacturer2;

    public function testFindByIds(): void {
        $this->em->clear();
        $manufacturersIds = [$this->manufacturer1->id, $this->manufacturer2->id];
        $manufacturers = $this->em->getRepository(Manufacturer::class)->findByIds($manufacturersIds);

        foreach ($manufacturers as $key => $manufacturer) {
            $this->assertInstanceOf(Manufacturer::class, $manufacturer);
            $this->assertSame($manufacturersIds[$key], $manufacturer->id);
        }
    }

    protected function createObjects(): void {
        $this->manufacturer1 = $this->dbService->createManufacturer($this->em, $this->fixtureService->getRandomString());
        $this->manufacturer2 = $this->dbService->createManufacturer($this->em, $this->fixtureService->getRandomString());
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteManufacturer($this->em, $this->manufacturer1->id);
        $this->dbService->deleteManufacturer($this->em, $this->manufacturer2->id);
    }
}
