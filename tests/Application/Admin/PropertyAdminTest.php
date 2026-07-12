<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\Property;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PropertyAdminTest extends AdminTestCase
{
    private Property $property;

    public function testList(): void {
        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/admin/app/property/list');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit(): void {
        $this->em->clear();
        $uri = '/admin/app/property/' . $this->property->id . '/edit';
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    #[\Override]
    protected function createObjects(): void {
        parent::createObjects();
        $this->property = $this->dbService->createProperty($this->em, $this->fixtureService->getRandomString());
    }

    #[\Override]
    protected function deleteObjects(): void {
        parent::deleteObjects();
        $this->dbService->deleteProperty($this->em, $this->property->id);
    }
}
