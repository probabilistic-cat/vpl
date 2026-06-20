<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Manufacturer;
use App\Tests\Helper\DBTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ManufacturerRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Manufacturer $manufacturer1;
    private Manufacturer $manufacturer2;

    public function testFindByIds(): void {
        $this->em->clear();
        $manufacturersIds = [$this->manufacturer1->getId(), $this->manufacturer2->getId()];
        $manufacturers = $this->em->getRepository(Manufacturer::class)->findByIds($manufacturersIds);

        foreach ($manufacturers as $key => $manufacturer) {
            $this->assertInstanceOf(Manufacturer::class, $manufacturer);
            $this->assertEquals($manufacturersIds[$key], $manufacturer->getId());
        }
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->manufacturer1 = DBTestHelper::createManufacturer($this->em);
        $this->manufacturer2 = DBTestHelper::createManufacturer($this->em);
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer1->getId());
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer2->getId());
        $this->em->close();
        $this->em = null;
    }
}
