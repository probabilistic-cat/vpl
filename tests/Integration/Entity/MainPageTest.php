<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Category;
use App\Entity\MainPage;
use App\Entity\Product;
use App\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\File\File;

class MainPageTest extends IntegrationTestCase
{
    private MainPage $mainPage;
    private Category $category;
    private Product $product1;
    private Product $product2;

    public function testRequiredProperties(): void {
        $this->em->refresh($this->mainPage);
        $this->assertNull($this->mainPage->modified);
    }

    public function testUpdate(): void {
        $this->em->refresh($this->mainPage);

        $phone = $this->fixtureService->getRandomString();
        $mail = $this->fixtureService->getRandomString();
        $facebook = $this->fixtureService->getRandomString();
        $copyroght = $this->fixtureService->getRandomString();
        $secondLine3Header = $this->fixtureService->getRandomString();
        $secondLine3Text = $this->fixtureService->getRandomString();
        $fourthLine1Header = $this->fixtureService->getRandomString();
        $fourthLine1Text = $this->fixtureService->getRandomString();
        $fourthLine2Header = $this->fixtureService->getRandomString();
        $fourthLine2Text = $this->fixtureService->getRandomString();
        $fourthLine3Header = $this->fixtureService->getRandomString();
        $fourthLine3Text = $this->fixtureService->getRandomString();
        $secondLine2ImgFile = $this->fixtureService->getImgFile();
        $secondLine2ImgFileContent = $secondLine2ImgFile->getContent();
        $fourthLine2ImgFile = $this->fixtureService->getImgFile();
        $fourthLine2ImgFileContent = $fourthLine2ImgFile->getContent();
        $fourthLine3ImgFile = $this->fixtureService->getImgFile();
        $fourthLine3ImgFileContent = $fourthLine3ImgFile->getContent();

        $this->mainPage->phone = $phone;
        $this->mainPage->mail = $mail;
        $this->mainPage->facebook = $facebook;
        $this->mainPage->copyright = $copyroght;
        $this->mainPage->secondLine1 = $this->product1;
        $this->mainPage->secondLine3Header = $secondLine3Header;
        $this->mainPage->secondLine3Text = $secondLine3Text;
        $this->mainPage->thirdLine1 = $this->product2;
        $this->mainPage->fourthLine1Header = $fourthLine1Header;
        $this->mainPage->fourthLine1Text = $fourthLine1Text;
        $this->mainPage->fourthLine2Header = $fourthLine2Header;
        $this->mainPage->fourthLine2Text = $fourthLine2Text;
        $this->mainPage->fourthLine3Header = $fourthLine3Header;
        $this->mainPage->fourthLine3Text = $fourthLine3Text;
        $this->mainPage->secondLine2ImgFile = $secondLine2ImgFile;
        $this->mainPage->fourthLine2ImgFile = $fourthLine2ImgFile;
        $this->mainPage->fourthLine3ImgFile = $fourthLine3ImgFile;

        $this->em->flush();

        $afterUpdateTs = new \DateTime()->getTimestamp();

        $this->em->refresh($this->mainPage);
        $this->assertSame($phone, $this->mainPage->phone);
        $this->assertSame($mail, $this->mainPage->mail);
        $this->assertSame($facebook, $this->mainPage->facebook);
        $this->assertSame($copyroght, $this->mainPage->copyright);
        $this->assertSame($this->product1->id, $this->mainPage->secondLine1->id);
        $this->assertSame($secondLine3Header, $this->mainPage->secondLine3Header);
        $this->assertSame($secondLine3Text, $this->mainPage->secondLine3Text);
        $this->assertSame($this->product2->id, $this->mainPage->thirdLine1->id);
        $this->assertSame($fourthLine1Header, $this->mainPage->fourthLine1Header);
        $this->assertSame($fourthLine1Text, $this->mainPage->fourthLine1Text);
        $this->assertSame($fourthLine2Header, $this->mainPage->fourthLine2Header);
        $this->assertSame($fourthLine2Text, $this->mainPage->fourthLine2Text);
        $this->assertSame($fourthLine3Header, $this->mainPage->fourthLine3Header);
        $this->assertSame($fourthLine3Text, $this->mainPage->fourthLine3Text);
        $secondLine2ImgFullPath = $this->imageStorage->getAbsolutePath($this->mainPage->secondLine2Img);
        $this->assertFileExists($secondLine2ImgFullPath);
        $this->assertSame($secondLine2ImgFileContent, new File($secondLine2ImgFullPath)->getContent());
        $fourthLine2ImgFullPath = $this->imageStorage->getAbsolutePath($this->mainPage->fourthLine2Img);
        $this->assertFileExists($fourthLine2ImgFullPath);
        $this->assertSame($fourthLine2ImgFileContent, new File($fourthLine2ImgFullPath)->getContent());
        $fourthLine3ImgFullPath = $this->imageStorage->getAbsolutePath($this->mainPage->fourthLine3Img);
        $this->assertFileExists($fourthLine3ImgFullPath);
        $this->assertSame($fourthLine3ImgFileContent, new File($fourthLine3ImgFullPath)->getContent());
        $this->assertNotNull($this->mainPage->modified);
        $this->assertTrue($this->mainPage->modified->getTimestamp() <= $afterUpdateTs);
    }

    protected function createObjects(): void {
        $this->mainPage = $this->dbService->createMainPage($this->em);
        $this->category = $this->dbService->createCategory($this->em, $this->fixtureService->getRandomString());
        $subcategory = $this->dbService->createSubcategory($this->em, $this->category, $this->fixtureService->getRandomString());
        $this->product1 = $this->dbService->createProduct($this->em, $subcategory, $this->fixtureService->getRandomString(), 1);
        $this->product2 = $this->dbService->createProduct($this->em, $subcategory, $this->fixtureService->getRandomString(), 2);
    }

    protected function deleteObjects(): void {
        $this->dbService->deleteMainPage($this->em, $this->mainPage->id);
        $this->dbService->deleteCategory($this->em, $this->category->id);
    }
}
