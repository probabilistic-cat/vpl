<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\ProductProperty;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: ProductProperty::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: ProductProperty::class)]
readonly class ProductPropertyListener extends BaseEntityListener
{
    public function prePersist(ProductProperty $productProperty, PrePersistEventArgs $args): void {
        $this->setImages($productProperty);
    }

    public function preUpdate(ProductProperty $productProperty, PreUpdateEventArgs $args): void {
        $this->setImages($productProperty);
    }

    private function setImages(ProductProperty $productProperty): void {
        $this->setImage(
            $productProperty->imgFile,
            $productProperty->img,
            ProductProperty::IMAGE_FOLDER,
            ProductProperty::IMAGE_NAME_PREFIX,
            static function (string $imagePath) use ($productProperty): void {
                $productProperty->img = $imagePath;
                $productProperty->imgFile = null;
            },
        );
    }
}
