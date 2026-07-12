<?php

declare(strict_types=1);

namespace App\Tests\Extension;

use App\Tests\Service\FixtureService;
use PHPUnit\Event\TestRunner\ExecutionFinished;
use PHPUnit\Event\TestRunner\ExecutionFinishedSubscriber;

readonly class AfterAllSubscriber implements ExecutionFinishedSubscriber
{
    public function __construct(
        private FixtureService $fixtureService,
    ) {}

    public function notify(ExecutionFinished $event): void {
        $this->fixtureService->cleanupFixtures();
    }
}
