<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Tests\Application\ApplicationTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SignControllerTest extends ApplicationTestCase
{
    public function testLogin(): void {
        $this->client->request(Request::METHOD_GET, '/login');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
