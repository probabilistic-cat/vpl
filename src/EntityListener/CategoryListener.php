<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Category::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Category::class)]
readonly class CategoryListener extends BaseEntityListener
{
    public function prePersist(Category $category, PrePersistEventArgs $args): void {
        $this->setImages($category);
    }

    public function preUpdate(Category $category, PreUpdateEventArgs $args): void {
        $this->setImages($category);
    }

    private function setImages(Category $category): void {
        $this->setImage(
            $category->imgFile,
            $category->img,
            Category::IMAGE_FOLDER,
            Category::IMAGE_NAME_PREFIX,
            static function (string $imagePath) use ($category): void {
                $category->img = $imagePath;
                $category->imgFile = null;
            },
        );
    }
}
