<?php

declare(strict_types=1);

namespace App\Entity\Common;

use App\Helper\FileHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait ImgFunctions
{
    private static function getFileName(
        string $extension,
        string $imgFolderName,
        ?string $imgNamePrefix = null,
    ): string {
        $imgNamePrefix = ($imgNamePrefix !== null) ? $imgNamePrefix : $imgFolderName . '_';
        return $imgNamePrefix . md5(uniqid('', true)) . '.' . $extension;
    }

    private static function uploadImgFile(
        ?UploadedFile $imgFile,
        string $imgFolderName,
        callable $callback,
        ?string $imgNamePrefix = null,
    ): void {
        if ($imgFile !== null) {
            $extension = $imgFile->getClientOriginalExtension();
            $fileName = self::getFileName($extension, $imgFolderName, $imgNamePrefix);

            $imgFile->move(FileHelper::DIR_PUBLIC . FileHelper::getImgFolder() . $imgFolderName . '/', $fileName);
            $callback(FileHelper::getImgFolder() . $imgFolderName . '/' . $fileName);
        }
    }

    private static function deleteImage(?string $img): void {
        if (($img !== null) && file_exists(FileHelper::DIR_PUBLIC . $img)) {
            unlink(FileHelper::DIR_PUBLIC . $img);
        }
    }
}
