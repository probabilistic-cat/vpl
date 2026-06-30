<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\MainPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainPageAdminTest extends AdminTestCase
{
    public function testList(): void {
        $this->client->request(Request::METHOD_GET, '/admin/app/mainpage/list');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit(): void {
        $mainPage = $this->em->getRepository(MainPage::class)->get();
        $uri = '/admin/app/mainpage/' . $mainPage->id . '/edit';
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
