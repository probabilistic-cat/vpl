<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Entity\Style;
use App\Entity\StyleImg;
use App\Entity\StyleInfoBottom;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DesignControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;
    private Style $style;
    private StyleImg $styleImg;
    private StyleInfoBottom $styleInfoBottom;

    public function testIndexWithRequiredProperties(): void {
        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/design');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexWithDependents(): void {
        $this->em->refresh($this->style);
        $this->createDependents();

        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/design');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexWithAllProperties(): void {
        $this->em->refresh($this->style);
        $this->createDependents();

        $this->styleImg->imgFile = TestHelper::getImgFile();
        $this->styleImg->imgColorFile = TestHelper::getImgFile();
        $this->styleInfoBottom->text = TestHelper::getRandomString();
        $this->em->flush();

        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/design');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function setUp(): void {
        parent::setUp();
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->style = DBTestHelper::createStyle($this->em, TestHelper::getRandomString(), 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteStyle($this->em, $this->style->id);
        $this->em->close();
    }

    private function createDependents(): void {
        $this->styleImg = DBTestHelper::createStyleImg($this->em, $this->style, 1);
        $this->styleInfoBottom =
            DBTestHelper::createStyleInfoBottom($this->em, $this->style, TestHelper::getRandomString(), 1)
        ;
    }
}
