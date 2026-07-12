<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\User;
use App\Tests\Application\ApplicationTestCase;

abstract class AdminTestCase extends ApplicationTestCase
{
    protected User $userAdmin;

    protected function setUp(): void {
        parent::setUp();

        $this->client->loginUser($this->userAdmin);
    }

    protected function createObjects(): void {
        $this->userAdmin = $this->dbService->createUser($this->em,
            $this->fixtureService->getRandomString(),
            $this->fixtureService->getRandomString(),
            $this->fixtureService->getRandomString(),
            'ROLE_ADMIN',
        );
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteUser($this->em, $this->userAdmin->id);
    }
}
