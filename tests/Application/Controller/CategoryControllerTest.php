<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Entity\Category;
use App\Entity\Subcategory;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;
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

        $this->category->description = TestHelper::getRandomString();
        $this->category->imgFile = TestHelper::getImgFile();
        $this->subcategory->description = TestHelper::getRandomString();
        $this->subcategory->imgFile = TestHelper::getImgFile();
        $this->em->flush();

        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/category/' . $this->category->id);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function setUp(): void {
        parent::setUp();
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em, TestHelper::getRandomString());
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteCategory($this->em, $this->category->id);
        $this->em->close();
    }

    private function createDependents(): void {
        $this->subcategory = DBTestHelper::createSubcategory($this->em, $this->category, TestHelper::getRandomString());
    }
}
