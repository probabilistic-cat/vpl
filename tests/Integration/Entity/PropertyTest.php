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

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $property = $this->em->getRepository(Property::class)->find($this->property->id);
        $this->assertSame($this->property->name, $property->name);
        $this->assertTrue($property->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($property->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $property = $this->em->getRepository(Property::class)->find($this->property->id);

        $name = TestHelper::getRandomString();
        $created = $property->created;

        $property->name = $name;
        $this->em->persist($property);
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $property2 = $this->em->getRepository(Property::class)->find($this->property->id);
        $this->assertSame($name, $property2->name);
        $this->assertSame($created->getTimestamp(), $property2->created->getTimestamp());
        $this->assertNotNull($property2->modified);
        $this->assertTrue($beforeUpdateTs <= $property2->modified->getTimestamp());
        $this->assertTrue($property2->modified->getTimestamp() <= $afterUpdateTs);
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
