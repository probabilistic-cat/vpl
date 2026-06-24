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

    public function testUser(): void {
        $beforeModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $user = $this->em->getRepository(User::class)->find($this->user);
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
        $this->assertTrue($user->created->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($user->modified);

        $user->role = 'abc,def,ghi';
        $user->active = true;
        $this->em->persist($user);
        $this->em->flush();

        $afterModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $user2 = $this->em->getRepository(User::class)->find($this->user);
        $this->assertSame(['abc', 'def', 'ghi'], $user2->getRoles());
        $this->assertSame($user->active, $user2->active);
        $this->assertEquals($user->created, $user2->created);
        $this->assertNotNull($user2->modified);
        $this->assertTrue($beforeModifyTs <= $user2->modified->getTimestamp());
        $this->assertTrue($user2->modified->getTimestamp() <= $afterModifyTs);
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
