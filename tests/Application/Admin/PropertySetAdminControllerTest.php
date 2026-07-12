<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\Property;
use App\Entity\PropertyItem;
use App\Entity\PropertySet;
use App\Service\ImageStorage;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PropertySetAdminControllerTest extends AdminTestCase
{
    private const string COPY_NAME_SUFFIX = ' (копия)';

    private ImageStorage $imageStorage;
    private Property $property;
    private PropertySet $propertySet;
    private PropertyItem $propertyItem;

    public function testClone(): void {
        $this->em->clear();
        $uri = '/admin/app/propertyset/' . $this->propertySet->id . '/clone';
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

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

        $imgContent = new File($this->imageStorage->getAbsolutePath($propertyItem->img))->getContent();
        $imgCopyContent = new File($this->imageStorage->getAbsolutePath($propertyItemCopy->img))->getContent();
        $this->assertSame($imgContent, $imgCopyContent);
    }

    protected function setUp(): void {
        parent::setUp();
        $this->imageStorage = static::getContainer()->get(ImageStorage::class);
    }

    #[\Override]
    protected function createObjects(): void {
        parent::createObjects();
        $this->property = $this->dbService->createProperty($this->em, $this->fixtureService->getRandomString());
        $this->propertySet = $this->dbService->createPropertySet($this->em, $this->property, $this->fixtureService->getRandomString());
        $this->propertyItem =
            $this->dbService->createPropertyItem($this->em, $this->propertySet, $this->fixtureService->getImgFile(), 1)
        ;
    }

    #[\Override]
    protected function deleteObjects(): void {
        parent::deleteObjects();
        $this->dbService->deleteProperty($this->em, $this->property->id);
    }
}
