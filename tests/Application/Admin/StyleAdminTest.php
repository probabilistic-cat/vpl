<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\Style;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StyleAdminTest extends AdminTestCase
{
    private EntityManagerInterface $em;
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

    protected function setUp(): void {
        parent::setUp();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->style = DBTestHelper::createStyle($this->em, TestHelper::getRandomString(), 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteStyle($this->em, $this->style->id);
        $this->em->close();
    }
}
