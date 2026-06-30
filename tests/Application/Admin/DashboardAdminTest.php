<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Tests\Application\ApplicationTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardAdminTest extends ApplicationTestCase
{
    public function testDashboard(): void {
        $this->client->request(Request::METHOD_GET, '/login');
        $this->client->submitForm('Log in', [
            '_username' => static::getContainer()->getParameter('admin_username'),
            '_password' => static::getContainer()->getParameter('admin_password'),
        ]);

        $this->client->request(Request::METHOD_GET, '/admin/dashboard');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
