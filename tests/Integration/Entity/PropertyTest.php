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
        $beforeModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $property = $this->em->getRepository(Property::class)->find($this->property->getId());
        $this->assertSame($this->property->getName(), $property->getName());
        $this->assertTrue($property->getCreated()->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($property->getModified());

        $property->setName(TestHelper::getRandomString());
        $this->em->persist($property);
        $this->em->flush();

        $afterModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $property2 = $this->em->getRepository(Property::class)->find($this->property->getId());
        $this->assertSame($property->getName(), $property2->getName());
        $this->assertEquals($property->getCreated(), $property2->getCreated());
        $this->assertNotNull($property2->getModified());
        $this->assertTrue($beforeModifyTs <= $property2->getModified()->getTimestamp());
        $this->assertTrue($property2->getModified()->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::$container->get(EntityManagerInterface::class);
        $this->property = DBTestHelper::createProperty($this->em);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteProperty($this->em, $this->property->getId());
        $this->em->close();
        $this->em = null;
    }
}
