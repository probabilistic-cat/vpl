<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\Manufacturer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ManufacturerAdminTest extends AdminTestCase
{
    private Manufacturer $manufacturer;

    public function testList(): void {
        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/admin/app/manufacturer/list');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit(): void {
        $this->em->clear();
        $uri = '/admin/app/manufacturer/' . $this->manufacturer->id . '/edit';
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    #[\Override]
    protected function createObjects(): void {
        parent::createObjects();
        $this->manufacturer = $this->dbService->createManufacturer($this->em, $this->fixtureService->getRandomString());
    }

    #[\Override]
    protected function deleteObjects(): void {
        parent::deleteObjects();
        $this->dbService->deleteManufacturer($this->em, $this->manufacturer->id);
    }
}
