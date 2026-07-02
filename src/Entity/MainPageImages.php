<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\ImgFunctions;
use App\Entity\Common\TimestampFields;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
#[ORM\Table(name: 'main_page_images')]
#[ORM\HasLifecycleCallbacks]
class MainPageImages
{
    use IdField;
    use ImgFunctions;
    use TimestampFields;

    private const string IMG_FOLDER_NAME = 'main_page';
    private const string IMG_NAME_PREFIX = 'first_line_1_img_';

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $img = null;

    #[ORM\Column(nullable: true)]
    public ?string $header = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $text = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modifiedNow();
        }
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function prePersistUpdateImg(): void {
        self::uploadImgFile($this->imgFile, self::IMG_FOLDER_NAME, function (string $img): void {
            $this->img = $img;
            $this->imgFile = null;
        }, self::IMG_NAME_PREFIX);
    }

    #[ORM\PostRemove]
    public function postRemoveImg(): void {
        self::deleteImage($this->img);
    }
}
