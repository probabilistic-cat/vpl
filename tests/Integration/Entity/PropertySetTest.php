<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Property;
use App\Entity\PropertySet;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PropertySetTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Property $property;
    private PropertySet $propertySet;

    public function testPropertySet(): void {
        $beforeModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $propertySet = $this->em->getRepository(PropertySet::class)->find($this->propertySet->id);
        $this->assertSame($this->property->id, $propertySet->property->id);
        $this->assertSame($this->propertySet->name, $propertySet->name);
        $this->assertTrue($propertySet->created->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($propertySet->modified);

        $propertySet->name = TestHelper::getRandomString();
        $this->em->persist($propertySet);
        $this->em->flush();

        $afterModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $propertySet2 = $this->em->getRepository(PropertySet::class)->find($this->propertySet->id);
        $this->assertSame($propertySet->name, $propertySet2->name);
        $this->assertEquals($propertySet->created, $propertySet2->created);
        $this->assertNotNull($propertySet2->modified);
        $this->assertTrue($beforeModifyTs <= $propertySet2->modified->getTimestamp());
        $this->assertTrue($propertySet2->modified->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->property = DBTestHelper::createProperty($this->em);
        $this->propertySet = DBTestHelper::createPropertySet($this->em, $this->property);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteProperty($this->em, $this->property->id);
        $this->em->close();
        $this->em = null;
    }
}
