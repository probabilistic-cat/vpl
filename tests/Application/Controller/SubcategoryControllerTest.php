<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\Subcategory;
use App\Tests\Application\ApplicationTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubcategoryControllerTest extends ApplicationTestCase
{
    private Category $category;
    private Subcategory $subcategory;
    private Product $product;
    private Manufacturer $manufacturer;

    public function testIndexWithRequiredProperties(): void {
        $this->em->clear();

        $this->client->request(Request::METHOD_GET, '/subcategory/' . $this->subcategory->id);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $invalidSubcategoryId = $this->subcategory->id + 1000;
        $this->client->request(Request::METHOD_GET, '/subcategory/' . $invalidSubcategoryId);
        $this->assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $invalidSubcategoryId = 'test';
        $this->client->request(Request::METHOD_GET, '/subcategory/' . $invalidSubcategoryId);
        $this->assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexWithDependents(): void {
        $this->em->refresh($this->category);
        $this->em->refresh($this->subcategory);
        $this->em->refresh($this->manufacturer);
        $this->createDependents();

        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/category/' . $this->category->id);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexWithAllProperties(): void {
        $this->em->refresh($this->category);
        $this->em->refresh($this->subcategory);
        $this->em->refresh($this->manufacturer);
        $this->createDependents();

        $this->fillProperties();
        $this->em->flush();

        $this->em->clear();
        $uri = '/subcategory/' . $this->subcategory->id;
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testManufacturerWithRequiredProperties(): void {
        $this->em->clear();

        $uri = '/subcategory/' . $this->subcategory->id . '?manufacturer=' . $this->manufacturer->id;
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $invalidManufacturerId = $this->manufacturer->id + 1000;
        $invalidUri = '/subcategory/' . $this->subcategory->id . '?manufacturer=' . $invalidManufacturerId;
        $this->client->request(Request::METHOD_GET, $invalidUri);
        $this->assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $invalidManufacturerId = 'test';
        $invalidUri = '/subcategory/' . $this->subcategory->id . '?manufacturer=' . $invalidManufacturerId;
        $this->client->request(Request::METHOD_GET, $invalidUri);
        $this->assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testManufacturerWithDependents(): void {
        $this->em->refresh($this->category);
        $this->em->refresh($this->subcategory);
        $this->em->refresh($this->manufacturer);
        $this->createDependents();

        $this->em->clear();

        $uri = '/subcategory/' . $this->subcategory->id . '?manufacturer=' . $this->manufacturer->id;
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testManufacturerWithAllProperties(): void {
        $this->em->refresh($this->category);
        $this->em->refresh($this->subcategory);
        $this->em->refresh($this->manufacturer);
        $this->createDependents();

        $this->fillProperties();
        $this->em->flush();

        $this->em->clear();
        $uri = '/subcategory/' . $this->subcategory->id . '?manufacturer=' . $this->manufacturer->id;
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function createObjects(): void {
        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $this->subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
        $this->manufacturer = $this->dbService->createManufacturer($this->em, $this->fixtureService->getRandomString());
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
        $this->dbService->deleteManufacturer($this->em, $this->manufacturer->id);
    }

    private function createDependents(): void {
        $this->product = $this->dbService->createProduct($this->em, $this->subcategory, $this->fixtureService->getRandomString(), 1);
        $this->dbService->createProductManufacturer($this->em, $this->product, $this->manufacturer, 1);
    }

    private function fillProperties(): void {
        $this->category->description = $this->fixtureService->getRandomString();
        $this->category->imgFile = $this->fixtureService->getImgFile();
        $this->subcategory->description = $this->fixtureService->getRandomString();
        $this->subcategory->imgFile = $this->fixtureService->getImgFile();
        $this->product->description = $this->fixtureService->getRandomString();
        $this->product->descriptionFull = $this->fixtureService->getRandomString();
        $this->product->seals = $this->fixtureService->getRandomString(2);
        $this->product->chambers = $this->fixtureService->getRandomString(3);
        $this->product->imgFile = $this->fixtureService->getImgFile();
        $this->manufacturer->imgFile = $this->fixtureService->getImgFile();
    }
}
