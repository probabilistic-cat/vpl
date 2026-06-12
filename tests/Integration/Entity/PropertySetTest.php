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
        $beforeModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $propertySet = $this->em->getRepository(PropertySet::class)->find($this->propertySet->getId());
        $this->assertSame($this->property->getId(), $propertySet->getProperty()->getId());
        $this->assertSame($this->propertySet->getName(), $propertySet->getName());
        $this->assertTrue($propertySet->getCreated()->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($propertySet->getModified());

        $propertySet->setName(TestHelper::getRandomString());
        $this->em->persist($propertySet);
        $this->em->flush();

        $afterModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $propertySet2 = $this->em->getRepository(PropertySet::class)->find($this->propertySet->getId());
        $this->assertSame($propertySet->getName(), $propertySet2->getName());
        $this->assertEquals($propertySet->getCreated(), $propertySet2->getCreated());
        $this->assertNotNull($propertySet2->getModified());
        $this->assertTrue($beforeModifyTs <= $propertySet2->getModified()->getTimestamp());
        $this->assertTrue($propertySet2->getModified()->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::$container->get(EntityManagerInterface::class);
        $this->property = DBTestHelper::createProperty($this->em);
        $this->propertySet = DBTestHelper::createPropertySet($this->em, $this->property);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteProperty($this->em, $this->property->getId());
        $this->em->close();
        $this->em = null;
    }
}
