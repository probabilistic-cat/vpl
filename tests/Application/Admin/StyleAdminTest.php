<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\Style;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StyleAdminTest extends AdminTestCase
{
    private Style $style;

    public function testList(): void {
        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/admin/app/style/list');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit(): void {
        $this->em->clear();
        $uri = '/admin/app/style/' . $this->style->id . '/edit';
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    #[\Override]
    protected function createObjects(): void {
        parent::createObjects();
        $this->style = $this->dbService->createStyle($this->em, $this->fixtureService->getRandomString(), 1);
    }

    #[\Override]
    protected function deleteObjects(): void {
        parent::deleteObjects();
        $this->dbService->deleteStyle($this->em, $this->style->id);
    }
}
