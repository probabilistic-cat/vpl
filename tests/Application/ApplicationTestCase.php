<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Tests\Service\DBService;
use App\Tests\Service\FixtureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApplicationTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected EntityManagerInterface $em;
    protected DBService $dbService;
    protected FixtureService $fixtureService;

    protected function setUp(): void {
        parent::setUp();

        $this->client = static::createClient();
        $this->client->disableReboot();

        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->dbService = static::getContainer()->get(DBService::class);
        $this->fixtureService = static::getContainer()->get(FixtureService::class);

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
