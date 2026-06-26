<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Style;
use App\Entity\StyleImg;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;

class StyleImgTest extends KernelTestCase
{
    private EntityManagerInterface $em;

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

        $imgFile = TestHelper::getImgFile();
        $imgFileContent = $imgFile->getContent();
        $imgColorFile = TestHelper::getImgFile();
        $imgColorFileContent = $imgColorFile->getContent();
        $created = $this->styleImg->created;

        $this->styleImg->imgFile = $imgFile;
        $this->styleImg->imgColorFile = $imgColorFile;
        $this->em->flush();

        $afterModify = new \DateTime()->getTimestamp();

        $this->em->refresh($this->styleImg);
        $imgFullPath = FileHelper::DIR_PUBLIC . $this->styleImg->img;
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $imgColorFullPath = FileHelper::DIR_PUBLIC . $this->styleImg->imgColor;
        $this->assertFileExists($imgColorFullPath);
        $this->assertSame($imgColorFileContent, new File($imgColorFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $this->styleImg->created->getTimestamp());
        $this->assertNotNull($this->styleImg->modified);
        $this->assertTrue($beforeModify <= $this->styleImg->modified->getTimestamp());
        $this->assertTrue($this->styleImg->modified->getTimestamp() <= $afterModify);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $this->seq = 1;

        $this->style = DBTestHelper::createStyle($this->em, TestHelper::getRandomString(), 1);
        $this->styleImg = DBTestHelper::createStyleImg($this->em, $this->style, $this->seq);
    }

    protected function tearDown(): void {
        parent::tearDown();
        DBTestHelper::deleteStyle($this->em, $this->style->id);
        $this->em->close();
    }
}
