<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\MainPageImages;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MainPageImagesTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;
    private MainPageImages $mainPageImages;

    public function testMainPageImages(): void {
        $beforeModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $mainPageImages = $this->em->getRepository(MainPageImages::class)->find($this->mainPageImages->getId());
        $this->assertSame($this->mainPageImages->seq, $mainPageImages->seq);
        $this->assertTrue($mainPageImages->getCreated()->getTimestamp() <= $beforeModifyTs);
        $this->assertNull($mainPageImages->getModified());

        $mainPageImages->header = TestHelper::getRandomString();
        $mainPageImages->text = TestHelper::getRandomString();
        $mainPageImages->imgFile = TestHelper::getImgFile();
        $this->em->persist($mainPageImages);
        $this->em->flush();

        $afterModifyTs = new \DateTime()->getTimestamp();
        $this->em->clear();
        $mainPageImages2 = $this->em->getRepository(MainPageImages::class)->find($this->mainPageImages->getId());
        $this->assertSame($mainPageImages->header, $mainPageImages2->header);
        $this->assertSame($mainPageImages->text, $mainPageImages2->text);
        $this->assertSame($mainPageImages->img, $mainPageImages2->img);
        $this->assertFileExists(FileHelper::DIR_PUBLIC . $mainPageImages2->img);
        $this->assertEquals($mainPageImages->getCreated(), $mainPageImages2->getCreated());
        $this->assertNotNull($mainPageImages2->getModified());
        $this->assertTrue($beforeModifyTs <= $mainPageImages2->getModified()->getTimestamp());
        $this->assertTrue($mainPageImages2->getModified()->getTimestamp() <= $afterModifyTs);
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
        DBTestHelper::deleteMainPageImages($this->em, $this->mainPageImages->getId());
        $this->em->close();
        $this->em = null;
    }
}
