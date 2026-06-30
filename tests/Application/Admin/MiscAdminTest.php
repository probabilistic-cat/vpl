<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\Misc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MiscAdminTest extends AdminTestCase
{
    public function testList(): void {
        $this->client->request(Request::METHOD_GET, '/admin/app/misc/list');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit(): void {
        $misc = $this->em->getRepository(Misc::class)->get();
        $uri = '/admin/app/misc/' . $misc->id . '/edit';
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
