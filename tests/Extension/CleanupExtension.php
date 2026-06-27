<?php

declare(strict_types=1);

namespace App\Tests\Extension;

use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use Symfony\Bridge\PhpUnit\SymfonyExtension;

class CleanupExtension extends SymfonyExtension
{
    #[\Override]
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void {
        parent::bootstrap($configuration, $facade, $parameters);
        $facade->registerSubscriber(new CleanupSubscriber());
    }
}
