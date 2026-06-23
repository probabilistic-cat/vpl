<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

abstract class AdminTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void {
        parent::setUp();
        $this->client = static::createClient();

        $this->client->request(Request::METHOD_GET, '/login');
        $this->client->submitForm('Log in', [
            '_username' => static::getContainer()->getParameter('admin_username'),
            '_password' => static::getContainer()->getParameter('admin_password'),
        ]);
    }
}
