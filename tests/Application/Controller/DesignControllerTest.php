<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Entity\Style;
use App\Entity\StyleImg;
use App\Entity\StyleInfoBottom;
use App\Tests\Application\ApplicationTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DesignControllerTest extends ApplicationTestCase
{
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

        $this->styleImg->imgFile = $this->fixtureService->getImgFile();
        $this->styleImg->imgColorFile = $this->fixtureService->getImgFile();
        $this->styleInfoBottom->text = $this->fixtureService->getRandomString();
        $this->em->flush();

        $this->em->clear();
        $this->client->request(Request::METHOD_GET, '/design');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    protected function createObjects(): void {
        $this->style = $this->dbService->createStyle($this->em, $this->fixtureService->getRandomString(), 1);
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteStyle($this->em, $this->style->id);
    }

    private function createDependents(): void {
        $this->styleImg = $this->dbService->createStyleImg($this->em, $this->style, 1);
        $this->styleInfoBottom =
            $this->dbService->createStyleInfoBottom($this->em, $this->style, $this->fixtureService->getRandomString(), 1)
        ;
    }
}
