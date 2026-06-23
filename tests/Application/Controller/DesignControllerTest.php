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
    private ?EntityManagerInterface $em;
    private Style $style;
    private StyleImg $styleImg;
    private StyleInfoBottom $styleInfoBottom;

    public function testIndexWithRequiredPropertiesOnly(): void {
        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/design');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexWithAllProperties(): void {
        $this->em->clear();

        $styleImg = $this->em->getRepository(StyleImg::class)->find($this->styleImg);
        $styleImg->imgFile = TestHelper::getImgFile();
        $styleImg->imgColorFile = TestHelper::getImgFile();
        $this->em->persist($styleImg);

        $styleInfoBottom = $this->em->getRepository(StyleInfoBottom::class)->find($this->styleInfoBottom);
        $styleInfoBottom->text = TestHelper::getRandomString();
        $this->em->persist($styleInfoBottom);

        $this->em->flush();

        $this->client->request(Request::METHOD_GET, '/design');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function setUp(): void {
        parent::setUp();
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->style = DBTestHelper::createStyle($this->em, 1);
        $this->styleImg = DBTestHelper::createStyleImg($this->em, $this->style, 1);
        $this->styleInfoBottom = DBTestHelper::createStyleInfoBottom($this->em, $this->style, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteStyle($this->em, $this->style->id);
        $this->em->close();
        $this->em = null;
    }
}
