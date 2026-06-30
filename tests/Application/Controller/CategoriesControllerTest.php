<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Entity\Category;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriesControllerTest extends ApplicationTestCase
{
    private Category $category;

    public function testIndexWithRequiredProperties(): void {
        $this->em->clear();

        $this->client->request(Request::METHOD_GET, '/categories');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexWithAllProperties(): void {
        $this->em->refresh($this->category);
        $this->category->description = TestHelper::getRandomString();
        $this->category->imgFile = TestHelper::getImgFile();
        $this->em->flush();

        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/categories');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function createObjects(): void {
        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteCategory($this->em, $this->category->id);
    }
}
