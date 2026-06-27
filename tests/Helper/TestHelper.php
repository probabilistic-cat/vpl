<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Helper\FileHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TestHelper
{
    private const string FIXTURES_IMG_DIR = FileHelper::DIR_PROJECT . 'tests/Fixtures/img/';
    private const string FIXTURE_IMG_NAME_PREFIX = 'img_test_';

    public static function getImgFile(): UploadedFile {
        $index = mt_rand(0, 9);
        $pathOrig = self::FIXTURES_IMG_DIR . self::getFileName((string)$index);
        $filenameCopy = self::getFileName($index . self::getRandomString(8));
        $pathCopy = self::FIXTURES_IMG_DIR . $filenameCopy;
        copy($pathOrig, $pathCopy);
        return new UploadedFile($pathCopy, $filenameCopy, 'image/jpeg', UPLOAD_ERR_OK, true);
    }

    public static function getRandomColor(): string {
        return sprintf('#%06x', mt_rand(0, 0xFFFFFF));
    }

    public static function getRandomString(int $maxLength = 32): string {
        $string = md5(uniqid('', true));
        return $maxLength < 32 ? substr($string, 0, $maxLength) : $string;
    }

    public static function cleanupFixtures(): void {
        $fixtureImgList = array_diff(scandir(self::FIXTURES_IMG_DIR), ['.', '..']);
        $deletedFiles = [];
        foreach ($fixtureImgList as $fileName) {
            if (preg_match('~^' . self::FIXTURE_IMG_NAME_PREFIX . '\d\.jpg$~', $fileName) !== 1) {
                @unlink(self::FIXTURES_IMG_DIR . $fileName);
                $deletedFiles[] = $fileName;
            }
        }

        if (count($deletedFiles) > 0) {
            echo "\n\nDeleted remaining files:\n- " . implode("\n- ", $deletedFiles);
        }
    }

    private static function getFileName(string $name): string {
        return self::FIXTURE_IMG_NAME_PREFIX . $name . '.jpg';
    }
}
