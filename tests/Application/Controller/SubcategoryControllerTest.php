<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\Subcategory;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
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
        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $this->subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
        $this->manufacturer = DBTestHelper::createManufacturer($this->em, TestHelper::getRandomString());
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer->id);
    }

    private function createDependents(): void {
        $this->product = DBTestHelper::createProduct($this->em, $this->subcategory, TestHelper::getRandomString(), 1);
        DBTestHelper::createProductManufacturer($this->em, $this->product, $this->manufacturer, 1);
    }

    private function fillProperties(): void {
        $this->category->description = TestHelper::getRandomString();
        $this->category->imgFile = TestHelper::getImgFile();
        $this->subcategory->description = TestHelper::getRandomString();
        $this->subcategory->imgFile = TestHelper::getImgFile();
        $this->product->description = TestHelper::getRandomString();
        $this->product->descriptionFull = TestHelper::getRandomString();
        $this->product->seals = TestHelper::getRandomString(2);
        $this->product->chambers = TestHelper::getRandomString(3);
        $this->product->imgFile = TestHelper::getImgFile();
        $this->manufacturer->imgFile = TestHelper::getImgFile();
    }
}
