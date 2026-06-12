<?php

declare(strict_types=1);

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminTest extends WebTestCase
{
    public function testLogin(): void {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/login');

        $container = static::$kernel->getContainer();
        $client->submitForm('Log in', [
            '_username' => $container->getParameter('admin_username'),
            '_password' => $container->getParameter('admin_password'),
        ]);

        $client->request(Request::METHOD_GET, '/admin/dashboard');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
