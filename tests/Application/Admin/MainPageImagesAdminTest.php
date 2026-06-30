<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\MainPageImages;
use App\Tests\Helper\DBTestHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainPageImagesAdminTest extends AdminTestCase
{
    private MainPageImages $mainPageImages;

    public function testList(): void {
        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/admin/app/mainpageimages/list');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit(): void {
        $this->em->clear();
        $uri = '/admin/app/mainpageimages/' . $this->mainPageImages->id . '/edit';
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    #[\Override]
    protected function createObjects(): void {
        parent::createObjects();
        $this->mainPageImages = DBTestHelper::createMainPageImages($this->em, 1);
    }

    #[\Override]
    protected function deleteObjects(): void {
        parent::deleteObjects();
        DBTestHelper::deleteMainPageImages($this->em, $this->mainPageImages->id);
    }
}
