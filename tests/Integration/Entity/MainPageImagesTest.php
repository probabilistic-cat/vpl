<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\MainPageImages;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use App\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\File\File;

class MainPageImagesTest extends IntegrationTestCase
{
    private int $seq;

    private MainPageImages $mainPageImages;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->mainPageImages);
        $this->assertSame($this->seq, $this->mainPageImages->seq);
        $this->assertTrue($this->mainPageImages->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($this->mainPageImages->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->mainPageImages);

        $header = TestHelper::getRandomString();
        $text = TestHelper::getRandomString();
        $imgFile = TestHelper::getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $this->mainPageImages->created;

        $this->mainPageImages->header = $header;
        $this->mainPageImages->text = $text;
        $this->mainPageImages->imgFile = $imgFile;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->mainPageImages);
        $this->assertSame($header, $this->mainPageImages->header);
        $this->assertSame($text, $this->mainPageImages->text);
        $imgFullPath = FileHelper::DIR_PUBLIC . $this->mainPageImages->img;
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->mainPageImages->created->getTimestamp());
        $this->assertNotNull($this->mainPageImages->modified);
        $this->assertTrue($beforeUpdateTs <= $this->mainPageImages->modified->getTimestamp());
        $this->assertTrue($this->mainPageImages->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function createObjects(): void {
        $this->seq = 1;

        $this->mainPageImages = DBTestHelper::createMainPageImages($this->em, $this->seq);
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteMainPageImages($this->em, $this->mainPageImages->id);
    }
}
