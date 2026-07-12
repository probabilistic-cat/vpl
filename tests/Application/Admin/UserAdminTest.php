<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAdminTest extends AdminTestCase
{
    private User $user;

    public function testList(): void {
        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/admin/app/user/list');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit(): void {
        $this->em->clear();
        $uri = '/admin/app/user/' . $this->user->id . '/edit';
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    #[\Override]
    protected function createObjects(): void {
        parent::createObjects();
        $this->user = $this->dbService->createUser(
            $this->em,
            $this->fixtureService->getRandomString(),
            $this->fixtureService->getRandomString(),
            $this->fixtureService->getRandomString(),
            $this->fixtureService->getRandomString(),
        );
    }

    #[\Override]
    protected function deleteObjects(): void {
        parent::deleteObjects();
        $this->dbService->deleteUser($this->em, $this->user->id);
    }
}
