<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\Manufacturer;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ManufacturerAdminTest extends AdminTestCase
{
    private EntityManagerInterface $em;
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

    protected function setUp(): void {
        parent::setUp();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->manufacturer = DBTestHelper::createManufacturer($this->em, TestHelper::getRandomString());
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer->id);
        $this->em->close();
    }
}
