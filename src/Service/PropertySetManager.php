<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\PropertySet;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class PropertySetManager
{
    private const string COPY_NAME_SUFFIX = ' (копия)';

    public function __construct(
        private ImageStorage $imageStorage,
        #[Autowire('%dir_tmp%')] private string $tmpDir,
    ) {}

    public function getCopy(PropertySet $propertySet): PropertySet {
        try {
            $clonedPropertySet = clone($propertySet, ['name' => $propertySet->name . self::COPY_NAME_SUFFIX]);
            $this->setPropertyItemsImages($clonedPropertySet);

            return $clonedPropertySet;
        } catch (\Throwable $e) {
            throw new \RuntimeException(
                'Unable to clone propertySet with id ' . $propertySet->id . ': ' . $e->getMessage(),
                $e->getCode(),
                $e,
            );
        }
    }

    private function setPropertyItemsImages(PropertySet $propertySet): void {
        $fs = new Filesystem();

        foreach ($propertySet->propertyItems as $propertyItem) {
            $origAbsolutePath = $this->imageStorage->getAbsolutePath($propertyItem->img);
            $extension = pathinfo($propertyItem->img, PATHINFO_EXTENSION);
            $copyName = md5(uniqid('', true)) . '.' . $extension;
            $copyAbsolutePath = $this->tmpDir . '/' . $copyName;
            $fs->copy($origAbsolutePath, $copyAbsolutePath);

            $file = new File($copyAbsolutePath);
            $uploadedFile = new UploadedFile($copyAbsolutePath, $copyName, $file->getMimeType(), null, true);
            $propertyItem->imgFile = $uploadedFile;

            unset($propertyItem->img);
        }
    }
}
