<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Property;
use App\Entity\PropertyItem;
use App\Entity\PropertySet;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PropertyItemTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Property $property;
    private PropertySet $propertySet;
    private PropertyItem $propertyItem;

    public function testPropertyItem(): void {
        $beforeModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $propertyItem = $this->em->getRepository(PropertyItem::class)->find($this->propertyItem);
        $this->assertSame($this->propertyItem->seq, $propertyItem->seq);
        $this->assertSame($this->propertyItem->img, $propertyItem->img);
        $this->assertFileExists(FileHelper::DIR_PUBLIC . $propertyItem->img);
        $this->assertTrue($propertyItem->getCreated()->getTimestamp() <= $beforeModifyTs);

        $propertySet = $this->em->getRepository(PropertySet::class)->find($this->propertySet);
        $propertyItem->propertySet = $propertySet;
        $propertyItem->name = TestHelper::getRandomString();
        $this->em->persist($propertyItem);
        $this->em->flush();

        $afterModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $propertyItem2 = $this->em->getRepository(PropertyItem::class)->find($this->propertyItem);
        $this->assertSame($this->propertySet->id, $propertyItem2->propertySet->id);
        $this->assertSame($propertyItem->name, $propertyItem2->name);
        $this->assertEquals($propertyItem->getCreated(), $propertyItem2->getCreated());
        $this->assertNotNull($propertyItem2->getModified());
        $this->assertTrue($beforeModifyTs <= $propertyItem2->getModified()->getTimestamp());
        $this->assertTrue($propertyItem2->getModified()->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->property = DBTestHelper::createProperty($this->em);
        $this->propertySet = DBTestHelper::createPropertySet($this->em, $this->property);
        $this->propertyItem = DBTestHelper::createPropertyItem($this->em, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteProperty($this->em, $this->property->id);
        $this->em->close();
        $this->em = null;
    }
}
