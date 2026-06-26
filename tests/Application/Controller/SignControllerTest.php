<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SignControllerTest extends WebTestCase
{
    public function testLogin(): void {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/login');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
