<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductInfoBottom;
use App\Entity\ProductInfoMiddle;
use App\Entity\ProductProperty;
use App\Entity\ProductType;
use App\Entity\Property;
use App\Entity\PropertySet;
use App\Entity\Subcategory;
use App\Tests\Application\ApplicationTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends ApplicationTestCase
{
    private Category $category;
    private Property $property;
    private Subcategory $subcategory;
    private Product $product;
    private ProductType $productType;
    private ProductProperty $productProperty;
    private ProductInfoBottom $productInfoBottom;
    private ProductInfoMiddle $productInfoMiddle;
    private PropertySet $propertySet;

    public function testIndexWithRequiredProperties(): void {
        $this->em->clear();

        $this->client->request(Request::METHOD_GET, '/product/' . $this->product->id);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $invalidProductId = $this->product->id + 1000;
        $this->client->request(Request::METHOD_GET, '/product/' . $invalidProductId);
        $this->assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $invalidProductId = 'test';
        $this->client->request(Request::METHOD_GET, '/product/' . $invalidProductId);
        $this->assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexWithDependents(): void {
        $this->em->refresh($this->category);
        $this->em->refresh($this->property);
        $this->em->refresh($this->product);
        $this->createDependents();

        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/category/' . $this->category->id);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexWithAllProperties(): void {
        $this->em->refresh($this->category);
        $this->em->refresh($this->property);
        $this->em->refresh($this->product);
        $this->createDependents();

        $this->category->description = $this->fixtureService->getRandomString();
        $this->category->imgFile = $this->fixtureService->getImgFile();
        $this->subcategory->description = $this->fixtureService->getRandomString();
        $this->subcategory->imgFile = $this->fixtureService->getImgFile();
        $this->product->description = $this->fixtureService->getRandomString();
        $this->product->descriptionFull = $this->fixtureService->getRandomString();
        $this->product->seals = $this->fixtureService->getRandomString(2);
        $this->product->chambers = $this->fixtureService->getRandomString(3);
        $this->product->imgFile = $this->fixtureService->getImgFile();
        $this->productType->imgFile = $this->fixtureService->getImgFile();
        $this->productProperty->propertySet = $this->propertySet;
        $this->productProperty->name = $this->fixtureService->getRandomString();
        $this->productProperty->imgFile = $this->fixtureService->getImgFile();
        $this->productInfoBottom->text = $this->fixtureService->getRandomString();
        $this->productInfoMiddle->name = $this->fixtureService->getRandomString();
        $this->productInfoMiddle->text = $this->fixtureService->getRandomString();
        $this->em->flush();

        $this->em->clear();
        $uri = '/product/' . $this->product->id;
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function createObjects(): void {
        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $this->property = $this->dbService->createProperty($this->em, $this->fixtureService->getRandomString());
        $this->subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
        $this->product = $this->dbService->createProduct($this->em, $this->subcategory, $this->fixtureService->getRandomString(), 1);
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteCategory($this->em, $this->category->id);
        $this->dbService->deleteProperty($this->em, $this->property->id);
    }

    private function createDependents(): void {
        $categoryProperty = $this->dbService->createCategoryProperty($this->em, $this->category, $this->property, 1);
        $this->productType =
            $this->dbService->createProductType($this->em, $this->product, $this->fixtureService->getRandomString(), 1)
        ;
        $this->propertySet = $this->dbService->createPropertySet($this->em, $this->property, $this->fixtureService->getRandomString());
        $this->productProperty =
            $this->dbService->createProductProperty($this->em, $this->product, $categoryProperty, 1)
        ;
        $this->productInfoBottom =
            $this->dbService->createProductInfoBottom($this->em, $this->product, $this->fixtureService->getRandomString(), 1)
        ;
        $this->productInfoMiddle = $this->dbService->createProductInfoMiddle($this->em, $this->product, 1);
        $this->dbService->createProductInfoMiddleGallery($this->em, $this->productInfoMiddle, $this->fixtureService->getImgFile(), 1);
    }
}
