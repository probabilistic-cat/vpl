<?php

declare(strict_types=1);

namespace App\Helper;

class FileHelper
{
    private const string IMG_FOLDER = 'img/';

    public const string DIR_PROJECT = __DIR__ . '/../../';
    public const string DIR_PUBLIC = self::DIR_PROJECT . 'public/';

    public static function getImgFolder(): string {
        return self::IMG_FOLDER;
    }
}
