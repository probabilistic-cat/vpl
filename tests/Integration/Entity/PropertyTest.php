<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Property;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PropertyTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Property $property;

    public function testProperty(): void {
        $beforeModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $property = $this->em->getRepository(Property::class)->find($this->property);
        $this->assertSame($this->property->name, $property->name);
        $this->assertTrue($property->getCreated()->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($property->getModified());

        $property->name = TestHelper::getRandomString();
        $this->em->persist($property);
        $this->em->flush();

        $afterModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $property2 = $this->em->getRepository(Property::class)->find($this->property);
        $this->assertSame($property->name, $property2->name);
        $this->assertEquals($property->getCreated(), $property2->getCreated());
        $this->assertNotNull($property2->getModified());
        $this->assertTrue($beforeModifyTs <= $property2->getModified()->getTimestamp());
        $this->assertTrue($property2->getModified()->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->property = DBTestHelper::createProperty($this->em);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteProperty($this->em, $this->property->id);
        $this->em->close();
        $this->em = null;
    }
}
