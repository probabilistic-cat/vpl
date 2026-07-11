<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Subcategory;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Subcategory::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Subcategory::class)]
readonly class SubcategoryListener extends BaseEntityListener
{
    public function prePersist(Subcategory $subcategory, PrePersistEventArgs $args): void {
        $this->setImages($subcategory);
    }

    public function preUpdate(Subcategory $subcategory, PreUpdateEventArgs $args): void {
        $this->setImages($subcategory);
    }

    private function setImages(Subcategory $subcategory): void {
        $this->setImage(
            $subcategory->imgFile,
            $subcategory->img,
            Subcategory::IMAGE_FOLDER,
            Subcategory::IMAGE_NAME_PREFIX,
            static function (string $imagePath) use ($subcategory): void {
                $subcategory->img = $imagePath;
                $subcategory->imgFile = null;
            },
        );
    }
}
