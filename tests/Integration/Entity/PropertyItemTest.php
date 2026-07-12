<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Property;
use App\Entity\PropertyItem;
use App\Entity\PropertySet;
use App\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\File\File;

class PropertyItemTest extends IntegrationTestCase
{
    private string $imgFileContent;
    private int $seq;

    private Property $property;
    private PropertySet $propertySet;
    private PropertyItem $propertyItem;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->propertyItem);
        $this->assertSame($this->seq, $this->propertyItem->seq);
        $imgFullPath = $this->imageStorage->getAbsolutePath($this->propertyItem->img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($this->imgFileContent, new File($imgFullPath)->getContent());
        $this->assertTrue($this->propertyItem->created->getTimestamp() <= $beforeUpdateTs);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->propertyItem);

        $name = $this->fixtureService->getRandomString();
        $created = $this->propertyItem->created;

        $this->propertyItem->name = $name;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->propertyItem);
        $this->assertSame($this->propertySet->id, $this->propertyItem->propertySet->id);
        $this->assertSame($name, $this->propertyItem->name);
        $this->assertSame($created->getTimestamp(), $this->propertyItem->created->getTimestamp());
        $this->assertNotNull($this->propertyItem->modified);
        $this->assertTrue($beforeUpdateTs <= $this->propertyItem->modified->getTimestamp());
        $this->assertTrue($this->propertyItem->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function createObjects(): void {
        $imgFile = $this->fixtureService->getImgFile();
        $this->imgFileContent = $imgFile->getContent();
        $this->seq = 1;

        $this->property = $this->dbService->createProperty($this->em, $this->fixtureService->getRandomString());
        $this->propertySet = $this->dbService->createPropertySet($this->em, $this->property, $this->fixtureService->getRandomString());
        $this->propertyItem = $this->dbService->createPropertyItem($this->em, $this->propertySet, $imgFile, $this->seq);
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteProperty($this->em, $this->property->id);
    }
}
