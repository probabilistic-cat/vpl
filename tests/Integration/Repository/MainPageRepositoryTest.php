<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\MainPage;
use App\Tests\Integration\IntegrationTestCase;

class MainPageRepositoryTest extends IntegrationTestCase
{
    public function testGet(): void {
        $this->em->clear();
        $mainPage = $this->em->getRepository(MainPage::class)->get();
        $this->assertSame(1, $mainPage->id);
    }
}
