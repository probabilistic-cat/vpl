<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Misc;
use App\Helper\FileHelper;
use App\Tests\Helper\DBTestHelper;
use App\Tests\Helper\TestHelper;
use App\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\File\File;

class MiscTest extends IntegrationTestCase
{
    private string $designName;
    private string $categoriesName;

    private Misc $misc;

    public function testRequiredProperties(): void {
        $this->em->refresh($this->misc);
        $this->assertSame($this->designName, $this->misc->designName);
        $this->assertSame($this->categoriesName, $this->misc->categoriesName);
        $this->assertNull($this->misc->modified);
    }

    public function testUpdate(): void {
        $this->em->refresh($this->misc);

        $designDescription = TestHelper::getRandomString();
        $categoriesDescription = TestHelper::getRandomString();
        $contactAddress = TestHelper::getRandomString();
        $contactMapSrc = TestHelper::getRandomString();
        $designImgFile = TestHelper::getImgFile();
        $designImgFileContent = $designImgFile->getContent();

        $this->misc->designDescription = $designDescription;
        $this->misc->categoriesDescription = $categoriesDescription;
        $this->misc->contactAddress = $contactAddress;
        $this->misc->contactMapSrc = $contactMapSrc;
        $this->misc->designImgFile = $designImgFile;
        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->misc);
        $this->assertSame($designDescription, $this->misc->designDescription);
        $this->assertSame($categoriesDescription, $this->misc->categoriesDescription);
        $this->assertSame($contactAddress, $this->misc->contactAddress);
        $this->assertSame($contactMapSrc, $this->misc->contactMapSrc);
        $imgFullPath = FileHelper::DIR_PUBLIC . $this->misc->designImg;
        $this->assertFileExists($imgFullPath);
        $this->assertSame($designImgFileContent, new File($imgFullPath)->getContent());
        $this->assertNotNull($this->misc->modified);
        $this->assertTrue($this->misc->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function createObjects(): void {
        $this->designName = TestHelper::getRandomString();
        $this->categoriesName = TestHelper::getRandomString();

        $this->misc = DBTestHelper::createMisc($this->em, $this->designName, $this->categoriesName);
    }

    protected function deleteObjects(): void {
        DBTestHelper::deleteMisc($this->em, $this->misc->id);
    }
}
