<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\User;
use App\Tests\Helper\DBTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAdminTest extends AdminTestCase
{
    private ?EntityManagerInterface $em;
    private User $user;

    public function testList(): void {
        $this->client->request(Request::METHOD_GET, '/admin/app/user/list');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit(): void {
        $uri = '/admin/app/user/' . $this->user->id . '/edit';
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function setUp(): void {
        parent::setUp();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->user = DBTestHelper::createUser($this->em);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteUser($this->em, $this->user->id);
        $this->em->close();
        $this->em = null;
    }
}
