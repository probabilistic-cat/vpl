<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class IntegrationTestCase extends KernelTestCase
{
    protected EntityManagerInterface $em;

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->em->getConnection()->beginTransaction();
        $this->createObjects();
    }

    protected function tearDown(): void {
        $this->em->clear();
        $this->deleteObjects();
        $this->em->getConnection()->rollback();
        $this->em->close();
        parent::tearDown();
    }

    protected function createObjects(): void {}

    protected function deleteObjects(): void {}
}
