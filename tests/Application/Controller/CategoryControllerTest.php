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
    private ?EntityManagerInterface $em;
    private Category $category;
    private Subcategory $subcategory;

    public function testIndexWithRequiredPropertiesOnly(): void {
        $this->em->clear();
        $category = $this->em->getRepository(Category::class)->find($this->category->getId());

        $this->client->request(Request::METHOD_GET, '/category/' . $category->getId());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $invalidCategoryId = $category->getId() + 1000;
        $this->client->request(Request::METHOD_GET, '/category/' . $invalidCategoryId);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $invalidCategoryId = 'test';
        $this->client->request(Request::METHOD_GET, '/category/' . $invalidCategoryId);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
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

        $this->em->flush();

        $this->client->request(Request::METHOD_GET, '/category/' . $category->getId());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function setUp(): void {
        parent::setUp();
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->category = DBTestHelper::createCategory($this->em);
        $this->subcategory = DBTestHelper::createSubcategory($this->em, $this->category);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteCategory($this->em, $this->category->getId());
        $this->em->close();
        $this->em = null;
    }
}
