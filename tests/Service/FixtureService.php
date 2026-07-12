<?php

declare(strict_types=1);

namespace App\Tests\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class FixtureService
{
    private const string FIXTURES_IMG_DIR = __DIR__ . '/../Fixtures/img/';
    private const string FIXTURES_IMG_VAR_DIR = __DIR__ . '/../../var/test/Fixtures/img/';
    private const string FIXTURE_IMG_NAME_PREFIX = 'img_test_';

    public function getImgFile(): UploadedFile {
        $index = mt_rand(0, 9);
        $filename = $this->getFileName((string)$index);
        $pathOrig = self::FIXTURES_IMG_DIR . $filename;

        $fs = new Filesystem();
        $filenameCopy = $this->getFileName($index . $this->getRandomString(8));
        $pathCopy = self::FIXTURES_IMG_VAR_DIR . '/' . $filenameCopy;
        $fs->copy($pathOrig, $pathCopy, true);

        return new UploadedFile($pathCopy, $filenameCopy, 'image/jpeg', UPLOAD_ERR_OK, true);
    }

    public function getRandomColor(): string {
        return sprintf('#%06x', mt_rand(0, 0xFFFFFF));
    }

    public function getRandomString(int $maxLength = 32): string {
        $string = md5(uniqid('', true));
        return $maxLength < 32 ? substr($string, 0, $maxLength) : $string;
    }

    public function cleanupFixtures(): void {
        $fixtureImgList = array_diff(scandir(self::FIXTURES_IMG_VAR_DIR), ['.', '..']);
        foreach ($fixtureImgList as $fileName) {
            unlink(self::FIXTURES_IMG_VAR_DIR . $fileName);
        }

        if (count($fixtureImgList) > 0) {
            echo "\n\nDeleted remaining files: " . count($fixtureImgList);
        }
    }

    private function getFileName(string $name): string {
        return self::FIXTURE_IMG_NAME_PREFIX . $name . '.jpg';
    }
}
