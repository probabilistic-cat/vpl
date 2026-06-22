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
    private ?EntityManagerInterface $em;
    private Category $category;
    private Property $property;
    private Subcategory $subcategory;
    private Product $product;
    private ProductType $productType;
    private ProductProperty $productProperty;
    private ProductInfoBottom $productInfoBottom;
    private ProductInfoMiddle $productInfoMiddle;
    private PropertySet $propertySet;

    public function testIndexWithRequiredPropertiesOnly(): void {
        $this->em->clear();
        $product = $this->em->getRepository(Product::class)->find($this->product->getId());

        $this->client->request(Request::METHOD_GET, '/product/' . $product->getId());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $invalidProductId = $product->getId() + 1000;
        $this->client->request(Request::METHOD_GET, '/product/' . $invalidProductId);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $invalidProductId = 'test';
        $this->client->request(Request::METHOD_GET, '/product/' . $invalidProductId);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexWithAllProperties(): void {
        $this->em->clear();

        $category = $this->em->getRepository(Category::class)->find($this->category->getId());
        $category->description = TestHelper::getRandomString();
        $category->imgFile = TestHelper::getImgFile();
        $this->em->persist($category);

        $subcategory = $this->em->getRepository(Subcategory::class)->find($this->subcategory->getId());
        $subcategory->description = TestHelper::getRandomString();
        $subcategory->imgFile = TestHelper::getImgFile();
        $this->em->persist($subcategory);

        $product = $this->em->getRepository(Product::class)->find($this->product->getId());
        $product->description = TestHelper::getRandomString();
        $product->descriptionFull = TestHelper::getRandomString();
        $product->seals = TestHelper::getRandomString(2);
        $product->chambers = TestHelper::getRandomString(3);
        $product->imgFile = TestHelper::getImgFile();
        $this->em->persist($product);

        $productType = $this->em->getRepository(ProductType::class)->find($this->productType->getId());
        $productType->imgFile = TestHelper::getImgFile();
        $this->em->persist($productType);

        $propertySet = $this->em->getRepository(PropertySet::class)->find($this->propertySet->getId());
        $productProperty = $this->em->getRepository(ProductProperty::class)->find($this->productProperty->getId());
        $productProperty->propertySet = $propertySet;
        $productProperty->name = TestHelper::getRandomString();
        $productProperty->imgFile = TestHelper::getImgFile();
        $this->em->persist($productProperty);

        $productInfoBottom = $this->em->getRepository(ProductInfoBottom::class)
            ->find($this->productInfoBottom->getId())
        ;
        $productInfoBottom->text = TestHelper::getRandomString();
        $this->em->persist($productInfoBottom);

        $productInfoMiddle = $this->em->getRepository(ProductInfoMiddle::class)
            ->find($this->productInfoMiddle->getId())
        ;
        $productInfoMiddle->name = TestHelper::getRandomString();
        $productInfoMiddle->text = TestHelper::getRandomString();
        $this->em->persist($productInfoMiddle);

        $this->em->flush();

        $uri = '/product/' . $product->getId();
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function setUp(): void {
        parent::setUp();
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $this->property = DBTestHelper::createProperty($this->em);
        $categoryProperty = DBTestHelper::createCategoryProperty($this->em, $this->category, $this->property, 1);
        $this->subcategory = DBTestHelper::createSubcategory($this->em, $this->category);
        $this->product = DBTestHelper::createProduct($this->em, $this->subcategory, 1);
        $this->productType =
            DBTestHelper::createProductType($this->em, $this->product, TestHelper::getRandomString(), 1)
        ;
        $this->propertySet = DBTestHelper::createPropertySet($this->em, $this->property);
        $this->productProperty =
            DBTestHelper::createProductProperty($this->em, $this->product, $categoryProperty, 1)
        ;
        $this->productInfoBottom = DBTestHelper::createProductInfoBottom($this->em, $this->product, 1);
        $this->productInfoMiddle = DBTestHelper::createProductInfoMiddle($this->em, $this->product, 1);
        DBTestHelper::createProductInfoMiddleGallery($this->em, $this->productInfoMiddle, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->getId());
        DBTestHelper::deleteProperty($this->em, $this->property->getId());
        $this->em->close();
        $this->em = null;
    }
}
