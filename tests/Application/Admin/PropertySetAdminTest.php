<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\Property;
use App\Entity\PropertySet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PropertySetAdminTest extends AdminTestCase
{
    private Property $property;
    private PropertySet $propertySet;

    public function testList(): void {
        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/admin/app/propertyset/list');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit(): void {
        $this->em->clear();
        $uri = '/admin/app/propertyset/' . $this->propertySet->id . '/edit';
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    #[\Override]
    protected function createObjects(): void {
        parent::createObjects();
        $this->property = $this->dbService->createProperty($this->em, $this->fixtureService->getRandomString());
        $this->propertySet = $this->dbService->createPropertySet($this->em, $this->property, $this->fixtureService->getRandomString());
    }

    #[\Override]
    protected function deleteObjects(): void {
        parent::deleteObjects();
        $this->dbService->deleteProperty($this->em, $this->property->id);
    }
}
