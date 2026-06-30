<?php

declare(strict_types=1);

namespace App\Tests\Application;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApplicationTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected EntityManagerInterface $em;

    protected function setUp(): void {
        parent::setUp();

        $this->client = static::createClient();
        $this->client->disableReboot();

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
