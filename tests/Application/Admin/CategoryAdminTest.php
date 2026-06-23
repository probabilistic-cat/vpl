<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\Category;
use App\Tests\Helper\DBTestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryAdminTest extends AdminTestCase
{
    private ?EntityManagerInterface $em;
    private Category $category;

    public function testList(): void {
        $this->client->request(Request::METHOD_GET, '/admin/app/category/list');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit(): void {
        $uri = '/admin/app/category/' . $this->category->id . '/edit';
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function setUp(): void {
        parent::setUp();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        $this->em->close();
        $this->em = null;
    }
}
