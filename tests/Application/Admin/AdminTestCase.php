<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\User;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;

abstract class AdminTestCase extends ApplicationTestCase
{
    protected User $userAdmin;

    protected function setUp(): void {
        parent::setUp();

        $this->client->loginUser($this->userAdmin);
    }

    protected function createObjects(): void {
        $this->userAdmin = DBTestHelper::createUser($this->em,
            TestHelper::getRandomString(),
            TestHelper::getRandomString(),
            TestHelper::getRandomString(),
            'ROLE_ADMIN',
        );
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteUser($this->em, $this->userAdmin->id);
    }
}
