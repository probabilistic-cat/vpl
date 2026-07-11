<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\BaseEntity;
use App\Service\ImageStorage;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::postRemove)]
readonly class LifecycleListener
{
    public function __construct(
        private ImageStorage $imageStorage,
    ) {}

    public function postRemove(PostRemoveEventArgs $args): void {
        $entity = $args->getObject();

        if ($entity instanceof BaseEntity) {
            $this->deleteImages($entity);
        }
    }

    private function deleteImages(BaseEntity $entity): void {
        $filePaths = array_filter($entity->getImagePaths());
        foreach ($filePaths as $filePath) {
            $this->imageStorage->delete($filePath);
        }
    }
}
