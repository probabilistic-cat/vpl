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

    public function testStyle(): void {
        $beforeModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $user = $this->em->getRepository(User::class)->find($this->user->getId());
        $this->assertSame($this->user->getName(), $user->getName());
        $this->assertSame($this->user->getPassword(), $user->getPassword());
        $this->assertSame($this->user->getMail(), $user->getMail());
        $this->assertSame($this->user->getRole(), $user->getRole());
        $this->assertSame(false, $user->getActive());
        $this->assertSame($this->user->getName(), $user->getUsername());
        $this->assertSame(
            [$this->user->getId(), $this->user->getName(), $this->user->getPassword()],
            $user->unserialize($user->serialize()),
        );
        $this->assertTrue($user->getCreated()->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($user->getModified());

        $user->setRole('abc,def,ghi');
        $user->setActive(true);
        $this->em->persist($user);
        $this->em->flush();

        $afterModifyTs = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $user2 = $this->em->getRepository(User::class)->find($this->user->getId());
        $this->assertSame(['abc', 'def', 'ghi'], $user2->getRoles());
        $this->assertSame($user->getActive(), $user2->getActive());
        $this->assertEquals($user->getCreated(), $user2->getCreated());
        $this->assertNotNull($user2->getModified());
        $this->assertTrue($beforeModifyTs <= $user2->getModified()->getTimestamp());
        $this->assertTrue($user2->getModified()->getTimestamp() <= $afterModifyTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::$container->get(EntityManagerInterface::class);
        $this->user = DBTestHelper::createUser($this->em);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteUser($this->em, $this->user->getId());
        $this->em->close();
        $this->em = null;
    }
}
