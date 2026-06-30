<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Manufacturer;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
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
        $this->manufacturer1 = DBTestHelper::createManufacturer($this->em, TestHelper::getRandomString());
        $this->manufacturer2 = DBTestHelper::createManufacturer($this->em, TestHelper::getRandomString());
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer1->id);
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer2->id);
    }
}
