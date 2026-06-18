<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\ProductManufacturer;
use App\Entity\Subcategory;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubcategoryControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ?EntityManagerInterface $em;
    private Category $category;
    private Subcategory $subcategory;
    private Product $product;
    private Manufacturer $manufacturer;

    public function testIndexWithRequiredPropertiesOnly(): void {
        $this->em->clear();
        $subcategory = $this->em->getRepository(Subcategory::class)->find($this->subcategory->getId());
        $uri = '/subcategory/' . $subcategory->getId();
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexWithAllProperties(): void {
        $this->em->clear();

        $category = $this->em->getRepository(Category::class)->find($this->category->getId());
        $category->setDescription(TestHelper::getRandomString());
        $category->setImgFile(TestHelper::getImgFile());
        $this->em->persist($category);

        $subcategory = $this->em->getRepository(Subcategory::class)->find($this->subcategory->getId());
        $subcategory->setDescription(TestHelper::getRandomString());
        $subcategory->setImgFile(TestHelper::getImgFile());
        $this->em->persist($subcategory);

        $product = $this->em->getRepository(Product::class)->find($this->product->getId());
        $product->setDescription(TestHelper::getRandomString());
        $product->setDescriptionFull(TestHelper::getRandomString());
        $product->setSeals(TestHelper::getRandomString(2));
        $product->setChambers(TestHelper::getRandomString(3));
        $product->setImgFile(TestHelper::getImgFile());
        $this->em->persist($product);

        $manufacturer = $this->em->getRepository(Manufacturer::class)->find($this->manufacturer->getId());
        $manufacturer->setImgFile(TestHelper::getImgFile());
        $this->em->persist($manufacturer);

        $this->em->flush();

        $uri = '/subcategory/' . $subcategory->getId();
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testManufacturerWithRequiredPropertiesOnly(): void {
        $this->em->clear();
        $subcategory = $this->em->getRepository(Subcategory::class)->find($this->subcategory->getId());
        $manufacturer = $this->em->getRepository(Manufacturer::class)->find($this->manufacturer->getId());
        $uri = '/subcategory/' . $subcategory->getId() . '?manufacturer=' . $manufacturer->getId();
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testManufacturerWithAllProperties(): void {
        $this->em->clear();
        $category = $this->em->getRepository(Category::class)->find($this->category->getId());
        $category->setDescription(TestHelper::getRandomString());
        $category->setImgFile(TestHelper::getImgFile());
        $this->em->persist($category);

        $subcategory = $this->em->getRepository(Subcategory::class)->find($this->subcategory->getId());
        $subcategory->setDescription(TestHelper::getRandomString());
        $subcategory->setImgFile(TestHelper::getImgFile());
        $this->em->persist($subcategory);

        $product = $this->em->getRepository(Product::class)->find($this->product->getId());
        $product->setDescription(TestHelper::getRandomString());
        $product->setDescriptionFull(TestHelper::getRandomString());
        $product->setSeals(TestHelper::getRandomString(2));
        $product->setChambers(TestHelper::getRandomString(3));
        $product->setImgFile(TestHelper::getImgFile());
        $this->em->persist($product);

        $manufacturer = $this->em->getRepository(Manufacturer::class)->find($this->manufacturer->getId());
        $manufacturer->setImgFile(TestHelper::getImgFile());
        $this->em->persist($manufacturer);

        $this->em->flush();

        $uri = '/subcategory/' . $subcategory->getId() . '?manufacturer=' . $manufacturer->getId();
        $this->client->request(Request::METHOD_GET, $uri);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function setUp(): void {
        parent::setUp();
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $this->subcategory = DBTestHelper::createSubcategory($this->em, $this->category);
        $this->product = DBTestHelper::createProduct($this->em, $this->subcategory, 1);
        $this->manufacturer = DBTestHelper::createManufacturer($this->em);
        DBTestHelper::createProductManufacturer($this->em, $this->product, $this->manufacturer, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->getId());
        DBTestHelper::deleteManufacturer($this->em, $this->manufacturer->getId());
        $this->em->close();
        $this->em = null;
    }
}
