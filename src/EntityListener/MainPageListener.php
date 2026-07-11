<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\MainPage;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: MainPage::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: MainPage::class)]
readonly class MainPageListener extends BaseEntityListener
{
    public function prePersist(MainPage $mainPage, PrePersistEventArgs $args): void {
        $this->setImages($mainPage);
    }

    public function preUpdate(MainPage $mainPage, PreUpdateEventArgs $args): void {
        $this->setImages($mainPage);
    }

    private function setImages(MainPage $mainPage): void {
        $this->setImage(
            $mainPage->secondLine2ImgFile,
            $mainPage->secondLine2Img,
            MainPage::IMAGE_FOLDER,
            MainPage::IMAGE_SECOND2_NAME_PREFIX,
            static function (string $imagePath) use ($mainPage): void {
                $mainPage->secondLine2Img = $imagePath;
                $mainPage->secondLine2ImgFile = null;
            },
        );

        $this->setImage(
            $mainPage->fourthLine2ImgFile,
            $mainPage->fourthLine2Img,
            MainPage::IMAGE_FOLDER,
            MainPage::IMAGE_FOURTH2_NAME_PREFIX,
            static function (string $imagePath) use ($mainPage): void {
                $mainPage->fourthLine2Img = $imagePath;
                $mainPage->fourthLine2ImgFile = null;
            },
        );

        $this->setImage(
            $mainPage->fourthLine3ImgFile,
            $mainPage->fourthLine3Img,
            MainPage::IMAGE_FOLDER,
            MainPage::IMAGE_FOURTH3_NAME_PREFIX,
            static function (string $imagePath) use ($mainPage): void {
                $mainPage->fourthLine3Img = $imagePath;
                $mainPage->fourthLine3ImgFile = null;
            },
        );
    }
}
