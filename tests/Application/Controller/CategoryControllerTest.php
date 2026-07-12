<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Entity\Category;
use App\Entity\Subcategory;
use App\Tests\Application\ApplicationTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends ApplicationTestCase
{
    private Category $category;
    private Subcategory $subcategory;

    public function testIndexWithRequiredProperties(): void {
        $this->em->clear();

        $this->client->request(Request::METHOD_GET, '/category/' . $this->category->id);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $invalidCategoryId = $this->category->id + 1000;
        $this->client->request(Request::METHOD_GET, '/category/' . $invalidCategoryId);
        $this->assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $invalidCategoryId = 'test';
        $this->client->request(Request::METHOD_GET, '/category/' . $invalidCategoryId);
        $this->assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexWithDependents(): void {
        $this->em->refresh($this->category);
        $this->createDependents();

        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/category/' . $this->category->id);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexWithAllProperties(): void {
        $this->em->refresh($this->category);
        $this->createDependents();

        $this->category->description = $this->fixtureService->getRandomString();
        $this->category->imgFile = $this->fixtureService->getImgFile();
        $this->subcategory->description = $this->fixtureService->getRandomString();
        $this->subcategory->imgFile = $this->fixtureService->getImgFile();
        $this->em->flush();

        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/category/' . $this->category->id);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function createObjects(): void {
        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
    }

    private function createDependents(): void {
        $this->subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
    }
}
