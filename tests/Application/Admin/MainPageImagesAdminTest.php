<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\MainPageImages;
use App\Tests\Helper\DBTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainPageImagesAdminTest extends AdminTestCase
{
    private EntityManagerInterface $em;
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

    protected function setUp(): void {
        parent::setUp();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->mainPageImages = DBTestHelper::createMainPageImages($this->em, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteMainPageImages($this->em, $this->mainPageImages->id);
        $this->em->close();
    }
}
