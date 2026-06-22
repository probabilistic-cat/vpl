<?php

declare(strict_types=1);

namespace App\Entity;

use App\Helper\FileHelper;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
#[ORM\Table(name: 'style_img')]
#[ORM\Index(name: 'ix__style_img__style_id', columns: ['style_id'])]
#[ORM\HasLifecycleCallbacks]
class StyleImg
{
    private const string IMG_FOLDER = 'img/style/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $img = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $imgColor = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: Style::class, cascade: ['persist'], inversedBy: 'styleImgs')]
    #[ORM\JoinColumn(name: 'style_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    public Style $style;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modified = new \DateTime();
        }
    }

    public ?UploadedFile $imgColorFile = null {
        set {
            $this->imgColorFile = $value;
            $this->modified = new \DateTime();
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function uploadImgFile(): void {
        if (!($this->imgFile instanceof UploadedFile)) {
            return;
        }

        $style = $this->style;

        $extension = $this->imgFile->getClientOriginalExtension();
        $fileName = 'style_' . $style->getId() . '_img_' . md5(uniqid('', true)) . '.' . $extension;
        $this->imgFile->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->img = self::IMG_FOLDER . $fileName;
        $this->imgFile = null;
    }

    public function uploadImgColorFile(): void {
        if (!($this->imgColorFile instanceof UploadedFile)) {
            return;
        }

        $style = $this->style;

        $extension = $this->imgColorFile->getClientOriginalExtension();
        $fileName = 'style_' . $style->getId() . '_img_color_' . md5(uniqid('', true)) . '.' . $extension;
        $this->imgColorFile->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->imgColor = self::IMG_FOLDER . $fileName;
        $this->imgColorFile = null;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function lifecycleImgFileUpload(): void {
        $this->uploadImgFile();
        $this->uploadImgColorFile();
    }

    #[ORM\PostRemove]
    public function removeImage(): void {
        if (($this->img !== null) && file_exists(FileHelper::DIR_PUBLIC . $this->img)) {
            @unlink(FileHelper::DIR_PUBLIC . $this->img);
        }
        if (($this->imgColor !== null) && file_exists(FileHelper::DIR_PUBLIC . $this->imgColor)) {
            @unlink(FileHelper::DIR_PUBLIC . $this->imgColor);
        }
    }
}
