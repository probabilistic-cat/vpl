<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Misc;
use App\Tests\Integration\IntegrationTestCase;

class MiscRepositoryTest extends IntegrationTestCase
{
    public function testGet(): void {
        $this->em->clear();
        $misc = $this->em->getRepository(Misc::class)->get();
        $this->assertSame(1, $misc->id);
    }
}
