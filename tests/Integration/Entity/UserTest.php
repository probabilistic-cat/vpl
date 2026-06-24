<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\User;
use App\Tests\Helper\DBTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private User $user;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $user = $this->em->getRepository(User::class)->find($this->user->id);
        $this->assertSame($this->user->name, $user->name);
        $this->assertSame($this->user->password, $user->password);
        $this->assertSame($this->user->mail, $user->mail);
        $this->assertSame($this->user->role, $user->role);
        $this->assertSame(false, $user->active);
        $this->assertSame($this->user->name, $user->getUserIdentifier());
        $this->assertSame(
            [$this->user->id, $this->user->name, $this->user->password],
            $user->unserialize($user->serialize()),
        );
        $this->assertTrue($user->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($user->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $user = $this->em->getRepository(User::class)->find($this->user->id);

        $roles = 'abc,def,ghi';
        $active = true;
        $created = $user->created;

        $user->role = $roles;
        $user->active = $active;
        $this->em->persist($user);
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $user2 = $this->em->getRepository(User::class)->find($this->user->id);
        $this->assertSame(explode(',', $roles), $user2->getRoles());
        $this->assertSame($active, $user2->active);
        $this->assertSame($created->getTimestamp(), $user2->created->getTimestamp());
        $this->assertNotNull($user2->modified);
        $this->assertTrue($beforeUpdateTs <= $user2->modified->getTimestamp());
        $this->assertTrue($user2->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
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
