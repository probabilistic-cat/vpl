<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\StyleImg;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: StyleImg::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: StyleImg::class)]
readonly class StyleImgListener extends BaseEntityListener
{
    public function prePersist(StyleImg $styleImg, PrePersistEventArgs $args): void {
        $this->setImages($styleImg);
    }

    public function preUpdate(StyleImg $styleImg, PreUpdateEventArgs $args): void {
        $this->setImages($styleImg);
    }

    private function setImages(StyleImg $styleImg): void {
        $this->setImage(
            $styleImg->imgFile,
            $styleImg->img,
            StyleImg::IMAGE_FOLDER,
            StyleImg::IMAGE_NAME_PREFIX,
            static function (string $imagePath) use ($styleImg): void {
                $styleImg->img = $imagePath;
                $styleImg->imgFile = null;
            },
        );

        $this->setImage(
            $styleImg->imgColorFile,
            $styleImg->imgColor,
            StyleImg::IMAGE_FOLDER,
            StyleImg::IMAGE_COLOR_NAME_PREFIX,
            static function (string $imagePath) use ($styleImg): void {
                $styleImg->imgColor = $imagePath;
                $styleImg->imgColorFile = null;
            },
        );
    }
}
