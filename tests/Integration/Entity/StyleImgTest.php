<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Style;
use App\Entity\StyleImg;
use App\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\File\File;

class StyleImgTest extends IntegrationTestCase
{
    private int $seq;

    private Style $style;
    private StyleImg $styleImg;

    public function testRequiredProperties(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->styleImg);
        $this->assertSame($this->style->id, $this->styleImg->style->id);
        $this->assertSame($this->seq, $this->styleImg->seq);
        $this->assertTrue($this->styleImg->created->getTimestamp() <= $beforeModify);
        $this->assertNull($this->styleImg->modified);
    }

    public function testUpdate(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->styleImg);

        $imgFile = $this->fixtureService->getImgFile();
        $imgFileContent = $imgFile->getContent();
        $imgColorFile = $this->fixtureService->getImgFile();
        $imgColorFileContent = $imgColorFile->getContent();
        $created = $this->styleImg->created;

        $this->styleImg->imgFile = $imgFile;
        $this->styleImg->imgColorFile = $imgColorFile;
        $this->em->flush();

        $afterModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->styleImg);
        $imgFullPath = $this->imageStorage->getAbsolutePath($this->styleImg->img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $imgColorFullPath = $this->imageStorage->getAbsolutePath($this->styleImg->imgColor);
        $this->assertFileExists($imgColorFullPath);
        $this->assertSame($imgColorFileContent, new File($imgColorFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->styleImg->created->getTimestamp());
        $this->assertNotNull($this->styleImg->modified);
        $this->assertTrue($beforeModify <= $this->styleImg->modified->getTimestamp());
        $this->assertTrue($this->styleImg->modified->getTimestamp() <= $afterModify);
    }

    protected function createObjects(): void {
        $this->seq = 1;

        $this->style = $this->dbService->createStyle($this->em, $this->fixtureService->getRandomString(), 1);
        $this->styleImg = $this->dbService->createStyleImg($this->em, $this->style, $this->seq);
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteStyle($this->em, $this->style->id);
    }
}
