<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\MainPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MainPageRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;

    public function testGet(): void {
        $this->em->clear();
        $mainPage = $this->em->getRepository(MainPage::class)->get();
        $this->assertEquals(1, $mainPage->getId());
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
