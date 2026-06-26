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
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;
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

        $this->category->description = TestHelper::getRandomString();
        $this->category->imgFile = TestHelper::getImgFile();
        $this->subcategory->description = TestHelper::getRandomString();
        $this->subcategory->imgFile = TestHelper::getImgFile();
        $this->product->description = TestHelper::getRandomString();
        $this->product->descriptionFull = TestHelper::getRandomString();
        $this->product->seals = TestHelper::getRandomString(2);
        $this->product->chambers = TestHelper::getRandomString(3);
        $this->product->imgFile = TestHelper::getImgFile();
        $this->productType->imgFile = TestHelper::getImgFile();
        $this->productProperty = $this->em->getRepository(ProductProperty::class)->find($this->productProperty->id);
        $this->productProperty->propertySet = $this->propertySet;
        $this->productProperty->name = TestHelper::getRandomString();
        $this->productProperty->imgFile = TestHelper::getImgFile();
        $this->productInfoBottom->text = TestHelper::getRandomString();
        $this->productInfoMiddle->name = TestHelper::getRandomString();
        $this->productInfoMiddle->text = TestHelper::getRandomString();
        $this->em->flush();

        $this->em->clear();
        $uri = '/product/' . $this->product->id;
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function setUp(): void {
        parent::setUp();
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
        $this->property = DBTestHelper::createProperty($this->em, TestHelper::getRandomString());
        $this->subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
        $this->product = DBTestHelper::createProduct($this->em, $this->subcategory, TestHelper::getRandomString(), 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        DBTestHelper::deleteProperty($this->em, $this->property->id);
        $this->em->close();
    }

    private function createDependents(): void {
        $categoryProperty = DBTestHelper::createCategoryProperty($this->em, $this->category, $this->property, 1);
        $this->productType =
            DBTestHelper::createProductType($this->em, $this->product, TestHelper::getRandomString(), 1)
        ;
        $this->propertySet = DBTestHelper::createPropertySet($this->em, $this->property, TestHelper::getRandomString());
        $this->productProperty =
            DBTestHelper::createProductProperty($this->em, $this->product, $categoryProperty, 1)
        ;
        $this->productInfoBottom =
            DBTestHelper::createProductInfoBottom($this->em, $this->product, TestHelper::getRandomString(), 1)
        ;
        $this->productInfoMiddle = DBTestHelper::createProductInfoMiddle($this->em, $this->product, 1);
        DBTestHelper::createProductInfoMiddleGallery($this->em, $this->productInfoMiddle, TestHelper::getImgFile(), 1);
    }
}
