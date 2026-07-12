<?php

declare(strict_types=1);

namespace App\Tests\Application\Admin;

use App\Entity\Category;
use App\Entity\Subcategory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubcategoryAdminTest extends AdminTestCase
{
    private Category $category;
    private Subcategory $subcategory;

    public function testList(): void {
        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/admin/app/subcategory/list');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit(): void {
        $this->em->clear();
        $uri = '/admin/app/subcategory/' . $this->subcategory->id . '/edit';
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    #[\Override]
    protected function createObjects(): void {
        parent::createObjects();
        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $this->subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
    }

    #[\Override]
    protected function deleteObjects(): void {
        parent::deleteObjects();
        $this->dbService->deleteCategory($this->em, $this->category->id);
    }
}
