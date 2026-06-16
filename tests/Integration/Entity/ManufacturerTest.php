<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Manufacturer;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ManufacturerTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Manufacturer $manufacturer;

    public function testManufacturer(): void {
        $beforeModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $manufacturer = $this->em->getRepository(Manufacturer::class)->find($this->manufacturer->getId());
        $this->assertSame($this->manufacturer->getName(), $manufacturer->getName());
        $this->assertTrue($manufacturer->getCreated()->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($manufacturer->getModified());

        $manufacturer->setImgFile(TestHelper::getImgFile());
        $this->em->persist($manufacturer);
        $this->em->flush();

        $afterModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $manufacturer2 = $this->em->getRepository(Manufacturer::class)->find($this->manufacturer->getId());
        $this->assertSame($manufacturer->getImg(), $manufacturer2->getImg());
        $this->assertFileExists(FileHelper::DIR_PUBLIC . $manufacturer2->getImg());
        $this->assertEquals($manufacturer->getCreated(), $manufacturer2->getCreated());
        $this->assertNotNull($manufacturer2->getModified());
        $this->assertTrue($beforeModifyTs <= $manufacturer2->getModified()->getTimestamp());
        $this->assertTrue($manufacturer2->getModified()->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->manufacturer = DBTestHelper::createManufacturer($this->em);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer->getId());
        $this->em->close();
        $this->em = null;
    }
}
