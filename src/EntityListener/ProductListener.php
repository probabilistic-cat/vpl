<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Product::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Product::class)]
readonly class ProductListener extends BaseEntityListener
{
    public function prePersist(Product $product, PrePersistEventArgs $args): void {
        $this->setImages($product);
    }

    public function preUpdate(Product $product, PreUpdateEventArgs $args): void {
        $this->setImages($product);
    }

    private function setImages(Product $product): void {
        $this->setImage(
            $product->imgFile,
            $product->img,
            Product::IMAGE_FOLDER,
            Product::IMAGE_NAME_PREFIX,
            static function (string $imagePath) use ($product): void {
                $product->img = $imagePath;
                $product->imgFile = null;
            },
        );
    }
}
