<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class ImageStorage
{
    public function __construct(
        #[Autowire('%dir_image%')] private string $imageDir,
    ) {}

    public function save(UploadedFile $uploadedFile, string $subfolder, string $namePrefix): string {
        $destination = $this->imageDir . '/' . $subfolder;
        $fileName = $namePrefix . '_' . md5(uniqid('', true)) . '.' . $uploadedFile->guessExtension();

        $uploadedFile->move($destination, $fileName);

        return $subfolder . '/' . $fileName;
    }

    public function delete(string $path): void {
        $absolutePath = $this->getAbsolutePath($path);
        $fs = new Filesystem();
        if ($fs->exists($absolutePath)) {
            $fs->remove($absolutePath);
        }
    }

    public function getAbsolutePath(string $path): string {
        return $this->imageDir . '/' . $path;
    }
}
