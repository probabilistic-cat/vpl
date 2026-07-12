<?php

declare(strict_types=1);

namespace App\Tests\Extension;

use App\Kernel;
use App\Tests\Service\FixtureService;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

readonly class TestsExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void {
        $kernel = new Kernel('test', true);
        $kernel->boot();

        /** @var FixtureService $fixtureService */
        $fixtureService = $kernel->getContainer()->get(FixtureService::class);

        $facade->registerSubscriber(new BeforeAllSubscriber());
        $facade->registerSubscriber(new AfterAllSubscriber($fixtureService));
    }
}
