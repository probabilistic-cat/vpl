<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\MainPageImages;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: MainPageImages::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: MainPageImages::class)]
readonly class MainPageImagesListener extends BaseEntityListener
{
    public function prePersist(MainPageImages $mainPageImages, PrePersistEventArgs $args): void {
        $this->setImages($mainPageImages);
    }

    public function preUpdate(MainPageImages $mainPageImages, PreUpdateEventArgs $args): void {
        $this->setImages($mainPageImages);
    }

    private function setImages(MainPageImages $mainPageImages): void {
        $this->setImage(
            $mainPageImages->imgFile,
            $mainPageImages->img,
            MainPageImages::IMAGE_FOLDER,
            MainPageImages::IMAGE_NAME_PREFIX,
            static function (string $imagePath) use ($mainPageImages): void {
                $mainPageImages->img = $imagePath;
                $mainPageImages->imgFile = null;
            },
        );
    }
}
