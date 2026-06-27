<?php

declare(strict_types=1);

namespace App\Tests\Extension;

use App\Tests\Helper\TestHelper;
use PHPUnit\Event\TestRunner\ExecutionFinished;
use PHPUnit\Event\TestRunner\ExecutionFinishedSubscriber;

class CleanupSubscriber implements ExecutionFinishedSubscriber
{
    public function notify(ExecutionFinished $event): void {
        TestHelper::cleanupFixtures();
    }
}
