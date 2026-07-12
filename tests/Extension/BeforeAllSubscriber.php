<?php

declare(strict_types=1);

namespace App\Tests\Extension;

use PHPUnit\Event\TestRunner\ExecutionStarted;
use PHPUnit\Event\TestRunner\ExecutionStartedSubscriber;

readonly class BeforeAllSubscriber implements ExecutionStartedSubscriber
{
    public function notify(ExecutionStarted $event): void {}
}
