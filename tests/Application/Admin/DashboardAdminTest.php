<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardAdminTest extends AdminTestCase
{
    public function testDashboard(): void {
        $this->client->request(Request::METHOD_GET, '/admin/dashboard');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
