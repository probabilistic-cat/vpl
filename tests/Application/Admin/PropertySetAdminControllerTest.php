<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\Property;
use App\Entity\PropertyItem;
use App\Entity\PropertySet;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PropertySetAdminControllerTest extends AdminTestCase
{
    private const string COPY_NAME_SUFFIX = ' (копия)';

    private ?EntityManagerInterface $em;
    private Property $property;
    private PropertySet $propertySet;
    private PropertyItem $propertyItem;

    public function testClone(): void {
        $uri = '/admin/app/propertyset/' . $this->propertySet->id . '/clone';
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        $this->em->clear();
        $propertySet = $this->em->getRepository(PropertySet::class)->find($this->propertySet->id);
        $propertyItem = $this->em->getRepository(PropertyItem::class)->find($this->propertyItem->id);
        $propertySetCopy = $this->em->getRepository(PropertySet::class)
            ->createQueryBuilder('propertySet')
            ->andWhere('propertySet.name = :name')
            ->andWhere('propertySet.created >= :created')
            ->setParameter('name', $propertySet->name . self::COPY_NAME_SUFFIX)
            ->setParameter('created', $propertySet->created)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        $this->assertSame(1, $propertySet->propertyItems->count());
        $this->assertSame($propertyItem->id, $propertySet->propertyItems->first()->id);

        $this->assertInstanceOf(PropertySet::class, $propertySetCopy);
        $this->assertNotEquals($propertySet->id, $propertySetCopy->id);
        $this->assertSame($propertySet->property->id, $propertySetCopy->property->id);
        $this->assertSame(1, $propertySetCopy->propertyItems->count());

        $propertyItemCopy = $propertySetCopy->propertyItems->first();
        $this->assertInstanceOf(PropertyItem::class, $propertyItemCopy);
        $this->assertNotEquals($propertyItem->id, $propertyItemCopy->id);
        $this->assertNotEquals($propertyItem->img, $propertyItemCopy->img);
        $this->assertTrue($propertyItem->created->getTimestamp() <= $propertyItemCopy->created->getTimestamp());

        $imgContent = new File(FileHelper::DIR_PUBLIC . $propertyItem->img)->getContent();
        $imgCopyContent = new File(FileHelper::DIR_PUBLIC . $propertyItemCopy->img)->getContent();
        $this->assertSame($imgContent, $imgCopyContent);
    }

    protected function setUp(): void {
        parent::setUp();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->property = DBTestHelper::createProperty($this->em);
        $this->propertySet = DBTestHelper::createPropertySet($this->em, $this->property);
        $this->propertyItem = DBTestHelper::createPropertyItem($this->em, 1);
        $this->propertySet->addPropertyItem($this->propertyItem);
        $this->em->flush();
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteProperty($this->em, $this->property->id);
        $this->em->close();
        $this->em = null;
    }
}
