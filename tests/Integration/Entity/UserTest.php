<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\User;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use App\Tests\Integration\IntegrationTestCase;

class UserTest extends IntegrationTestCase
{
    private const bool ACTIVE_DEFAULT = false;

    private string $name;
    private string $password;
    private string $mail;
    private string $role;

    private User $user;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->user);
        $this->assertSame($this->name, $this->user->name);
        $this->assertSame($this->password, $this->user->password);
        $this->assertSame($this->mail, $this->user->mail);
        $this->assertSame($this->role, $this->user->role);
        $this->assertSame(self::ACTIVE_DEFAULT, $this->user->active);
        $this->assertSame($this->user->name, $this->user->getUserIdentifier());
        $this->assertSame(
            [$this->user->id, $this->user->name, $this->user->password],
            $this->user->unserialize($this->user->serialize()),
        );
        $this->assertTrue($this->user->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->user->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->user);

        $roles = 'abc,def,ghi';
        $active = !self::ACTIVE_DEFAULT;
        $created = $this->user->created;

        $this->user->role = $roles;
        $this->user->active = $active;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->user);
        $this->assertSame(explode(',', $roles), $this->user->getRoles());
        $this->assertSame($active, $this->user->active);
        $this->assertSame($created->getTimestamp(), $this->user->created->getTimestamp());
        $this->assertNotNull($this->user->modified);
        $this->assertTrue($beforeUpdateTs <= $this->user->modified->getTimestamp());
        $this->assertTrue($this->user->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function createObjects(): void {
        $this->name = TestHelper::getRandomString();
        $this->password = TestHelper::getRandomString();
        $this->mail = TestHelper::getRandomString();
        $this->role = TestHelper::getRandomString();

        $this->user = DBTestHelper::createUser($this->em, $this->name, $this->password, $this->mail, $this->role);
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteUser($this->em, $this->user->id);
    }
}
