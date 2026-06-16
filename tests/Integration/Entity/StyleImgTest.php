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

class StyleImgTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private Style $style;
    private StyleImg $styleImg;

    public function testStyleImg(): void {
        $beforeModify = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $styleImg = $this->em->getRepository(StyleImg::class)->find($this->styleImg->getId());
        $this->assertSame($this->style->getId(), $styleImg->getStyle()->getId());
        $this->assertSame($this->styleImg->getSeq(), $styleImg->getSeq());
        $this->assertTrue($styleImg->getCreated()->getTimestamp() <= $beforeModify);
        $this->assertNull($styleImg->getModified());

        $styleImg->setImgFile(TestHelper::getImgFile());
        $styleImg->setImgColorFile(TestHelper::getImgFile());
        $this->em->persist($styleImg);
        $this->em->flush();

        $afterModify = (new \DateTime())->getTimestamp();
        $this->em->clear();
        $styleImg2 = $this->em->getRepository(StyleImg::class)->find($this->styleImg->getId());
        $this->assertSame($styleImg->getImg(), $styleImg2->getImg());
        $this->assertFileExists(FileHelper::DIR_PUBLIC . $styleImg2->getImg());
        $this->assertSame($styleImg->getImgColor(), $styleImg2->getImgColor());
        $this->assertFileExists(FileHelper::DIR_PUBLIC . $styleImg2->getImgColor());
        $this->assertEquals($styleImg->getCreated(), $styleImg2->getCreated());
        $this->assertNotNull($styleImg2->getModified());
        $this->assertTrue($beforeModify <= $styleImg2->getModified()->getTimestamp());
        $this->assertTrue($styleImg2->getModified()->getTimestamp() <= $afterModify);
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
        DBTestHelper::deleteStyle($this->em, $this->style->getId());
        $this->em->close();
        $this->em = null;
    }
}
