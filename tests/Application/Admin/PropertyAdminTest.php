<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\Property;
use App\Tests\Helper\DBTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PropertyAdminTest extends AdminTestCase
{
    private ?EntityManagerInterface $em;
    private Property $property;

    public function testList(): void {
        $this->client->request(Request::METHOD_GET, '/admin/app/property/list');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit(): void {
        $uri = '/admin/app/property/' . $this->property->id . '/edit';
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function setUp(): void {
        parent::setUp();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->property = DBTestHelper::createProperty($this->em);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteProperty($this->em, $this->property->id);
        $this->em->close();
        $this->em = null;
    }
}
