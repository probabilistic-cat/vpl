<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Misc;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Misc::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Misc::class)]
readonly class MiscListener extends BaseEntityListener
{
    public function prePersist(Misc $misc, PrePersistEventArgs $args): void {
        $this->setImages($misc);
    }

    public function preUpdate(Misc $misc, PreUpdateEventArgs $args): void {
        $this->setImages($misc);
    }

    private function setImages(Misc $misc): void {
        $this->setImage(
            $misc->designImgFile,
            $misc->designImg ?? null,
            Misc::IMAGE_FOLDER,
            Misc::IMAGE_NAME_PREFIX,
            static function (string $imagePath) use ($misc): void {
                $misc->designImg = $imagePath;
                $misc->designImgFile = null;
            },
        );
    }
}
