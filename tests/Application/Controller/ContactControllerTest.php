<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Tests\Application\ApplicationTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactControllerTest extends ApplicationTestCase
{
    public function testIndex(): void {
        $this->client->request(Request::METHOD_GET, '/contact');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
