<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Manufacturer;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Manufacturer::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Manufacturer::class)]
readonly class ManufacturerListener extends BaseEntityListener
{
    public function prePersist(Manufacturer $manufacturer, PrePersistEventArgs $args): void {
        $this->setImages($manufacturer);
    }

    public function preUpdate(Manufacturer $manufacturer, PreUpdateEventArgs $args): void {
        $this->setImages($manufacturer);
    }

    private function setImages(Manufacturer $manufacturer): void {
        $this->setImage(
            $manufacturer->imgFile,
            $manufacturer->img,
            Manufacturer::IMAGE_FOLDER,
            Manufacturer::IMAGE_NAME_PREFIX,
            static function (string $imagePath) use ($manufacturer): void {
                $manufacturer->img = $imagePath;
                $manufacturer->imgFile = null;
            },
        );
    }
}
