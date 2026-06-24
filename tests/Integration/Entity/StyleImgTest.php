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
    private ?EntityManagerInterface $em;
    private Style $style;
    private StyleImg $styleImg;

    public function testRequiredProperties(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->clear();
        $styleImg = $this->em->getRepository(StyleImg::class)->find($this->styleImg->id);
        $this->assertSame($this->style->id, $styleImg->style->id);
        $this->assertSame($this->styleImg->seq, $styleImg->seq);
        $this->assertTrue($styleImg->created->getTimestamp() <= $beforeModify);
        $this->assertNull($styleImg->modified);
    }

    public function testUpdate(): void {
        $beforeModify = new \DateTime()->getTimestamp();

        $this->em->clear();
        $styleImg = $this->em->getRepository(StyleImg::class)->find($this->styleImg->id);

        $imgFile = TestHelper::getImgFile();
        $imgFileContent = $imgFile->getContent();
        $imgColorFile = TestHelper::getImgFile();
        $imgColorFileContent = $imgColorFile->getContent();
        $created = $styleImg->created;

        $styleImg->imgFile = $imgFile;
        $styleImg->imgColorFile = $imgColorFile;
        $this->em->persist($styleImg);
        $this->em->flush();

        $afterModify = new \DateTime()->getTimestamp();

        $this->em->clear();
        $styleImg2 = $this->em->getRepository(StyleImg::class)->find($this->styleImg->id);
        $imgFullPath = FileHelper::DIR_PUBLIC . $styleImg2->img;
        $imgColorFullPath = FileHelper::DIR_PUBLIC . $styleImg2->imgColor;
        $this->assertSame($styleImg->img, $styleImg2->img);
        $this->assertFileExists($imgFullPath);
        $this->assertSame($imgFileContent, new File($imgFullPath)->getContent());
        $this->assertSame($styleImg->imgColor, $styleImg2->imgColor);
        $this->assertFileExists($imgColorFullPath);
        $this->assertSame($imgColorFileContent, new File($imgColorFullPath)->getContent());
        $this->assertSame($created->getTimestamp(), $styleImg2->created->getTimestamp());
        $this->assertNotNull($styleImg2->modified);
        $this->assertTrue($beforeModify <= $styleImg2->modified->getTimestamp());
        $this->assertTrue($styleImg2->modified->getTimestamp() <= $afterModify);
    }

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->style = DBTestHelper::createStyle($this->em, 1);
        $this->styleImg = DBTestHelper::createStyleImg($this->em, $this->style, 1);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->em->clear();
        DBTestHelper::deleteStyle($this->em, $this->style->id);
        $this->em->close();
        $this->em = null;
    }
}
