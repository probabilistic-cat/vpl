<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\PropertyItem;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: PropertyItem::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: PropertyItem::class)]
readonly class PropertyItemListener extends BaseEntityListener
{
    public function prePersist(PropertyItem $propertyItem, PrePersistEventArgs $args): void {
        $this->setImages($propertyItem);
    }

    public function preUpdate(PropertyItem $propertyItem, PreUpdateEventArgs $args): void {
        $this->setImages($propertyItem);
    }

    private function setImages(PropertyItem $propertyItem): void {
        $this->setImage(
            $propertyItem->imgFile,
            $propertyItem->img ?? null,
            PropertyItem::IMAGE_FOLDER,
            PropertyItem::IMAGE_NAME_PREFIX,
            static function (string $imagePath) use ($propertyItem): void {
                $propertyItem->img = $imagePath;
                $propertyItem->imgFile = null;
            },
        );
    }
}
