<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\ProductInfoMiddleGallery;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: ProductInfoMiddleGallery::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: ProductInfoMiddleGallery::class)]
readonly class ProductInfoMiddleGalleryListener extends BaseEntityListener
{
    public function prePersist(ProductInfoMiddleGallery $productInfoMiddleGallery, PrePersistEventArgs $args): void {
        $this->setImages($productInfoMiddleGallery);
    }

    public function preUpdate(ProductInfoMiddleGallery $productInfoMiddleGallery, PreUpdateEventArgs $args): void {
        $this->setImages($productInfoMiddleGallery);
    }

    private function setImages(ProductInfoMiddleGallery $productInfoMiddleGallery): void {
        $this->setImage(
            $productInfoMiddleGallery->imgFile,
            $productInfoMiddleGallery->img ?? null,
            ProductInfoMiddleGallery::IMAGE_FOLDER,
            ProductInfoMiddleGallery::IMAGE_NAME_PREFIX,
            static function (string $imagePath) use ($productInfoMiddleGallery): void {
                $productInfoMiddleGallery->img = $imagePath;
                $productInfoMiddleGallery->imgFile = null;
            },
        );
    }
}
