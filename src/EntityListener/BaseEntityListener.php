<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Service\ImageStorage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class BaseEntityListener
{
    public function __construct(
        private ImageStorage $imageStorage,
    ) {}

    protected function setImage(
        ?UploadedFile $imageFile,
        ?string $imageOldPath,
        string $imageFolder,
        string $namePrefix,
        callable $callback,
    ): void {
        if ($imageFile === null) {
            return;
        }

        if ($imageOldPath !== null) {
            $this->imageStorage->delete($imageOldPath);
        }

        $imagePath = $this->imageStorage->save($imageFile, $imageFolder, $namePrefix);
        $callback($imagePath);
    }
}
