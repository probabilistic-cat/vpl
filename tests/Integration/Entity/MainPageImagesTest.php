<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\MainPageImages;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;

class MainPageImagesTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private MainPageImages $mainPageImages;

    public function testRequiredProperties(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $mainPageImages = $this->em->getRepository(MainPageImages::class)->find($this->mainPageImages->id);
        $this->assertSame($this->mainPageImages->seq, $mainPageImages->seq);
        $this->assertTrue($mainPageImages->created->getTimestamp() <= $beforeUpdateTs);
        $this->assertNull($mainPageImages->modified);
    }

    public function testUpdate(): void {
        $beforeUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $mainPageImages = $this->em->getRepository(MainPageImages::class)->find($this->mainPageImages->id);

        $header = TestHelper::getRandomString();
        $text = TestHelper::getRandomString();
        $imgFile = TestHelper::getImgFile();
        $imgFileContent = $imgFile->getContent();
        $created = $mainPageImages->created;

        $mainPageImages->header = $header;
        $mainPageImages->text = $text;
        $mainPageImages->imgFile = $imgFile;
        $this->em->persist($mainPageImages);
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->clear();
        $mainPageImages2 = $this->em->getRepository(MainPageImages::class)->find($this->mainPageImages->id);
        $imgFullPath = FileHelper::DIR_PUBLIC . $mainPageImages2->img;
        $this->assertSame($header, $mainPageImages2->header);
        $this->assertSame($text, $mainPageImages2->text);
        $this->assertSame($mainPageImages->img, $mainPageImages2->img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $mainPageImages2->created->getTimestamp());
        $this->assertNotNull($mainPageImages2->modified);
        $this->assertTrue($beforeUpdateTs <= $mainPageImages2->modified->getTimestamp());
        $this->assertTrue($mainPageImages2->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->mainPageImages = DBTestHelper::createMainPageImages($this->em, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteMainPageImages($this->em, $this->mainPageImages->id);
        $this->em->close();
        $this->em = null;
    }
}
