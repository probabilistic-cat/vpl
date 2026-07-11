<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\ProductType;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: ProductType::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: ProductType::class)]
readonly class ProductTypeListener extends BaseEntityListener
{
    public function prePersist(ProductType $productType, PrePersistEventArgs $args): void {
        $this->setImages($productType);
    }

    public function preUpdate(ProductType $productType, PreUpdateEventArgs $args): void {
        $this->setImages($productType);
    }

    private function setImages(ProductType $productType): void {
        $this->setImage(
            $productType->imgFile,
            $productType->img,
            ProductType::IMAGE_FOLDER,
            ProductType::IMAGE_NAME_PREFIX,
            static function (string $imagePath) use ($productType): void {
                $productType->img = $imagePath;
                $productType->imgFile = null;
            },
        );
    }
}
