<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Misc;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MiscRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;

    public function testGet(): void {
        $this->em->clear();
        $misc = $this->em->getRepository(Misc::class)->get();
        $this->assertEquals(1, $misc->getId());
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->close();
        $this->em = null;
    }
}
