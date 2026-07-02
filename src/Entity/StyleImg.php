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
#[ORM\Table(name: 'style_img')]
#[ORM\Index(name: 'ix__style_img__style_id', columns: ['style_id'])]
#[ORM\HasLifecycleCallbacks]
class StyleImg
{
    use IdField;
    use ImgFunctions;
    use TimestampFields;

    private const string IMG_FOLDER_NAME = 'style';
    private const string IMG_NAME_PREFIX = 'style_img_';
    private const string IMG_COLOR_NAME_PREFIX = 'style_img_color_';

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $img = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $imgColor = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\ManyToOne(targetEntity: Style::class, inversedBy: 'styleImgs')]
    #[ORM\JoinColumn(name: 'style_id', referencedColumnName: 'id', nullable: false)]
    public Style $style;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modifiedNow();
        }
    }

    public ?UploadedFile $imgColorFile = null {
        set {
            $this->imgColorFile = $value;
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
        self::uploadImgFile($this->imgColorFile, self::IMG_FOLDER_NAME, function (string $img): void {
            $this->imgColor = $img;
            $this->imgColorFile = null;
        }, self::IMG_COLOR_NAME_PREFIX);
    }

    #[ORM\PostRemove]
    public function postRemoveImg(): void {
        self::deleteImage($this->img);
        self::deleteImage($this->imgColor);
    }
}
