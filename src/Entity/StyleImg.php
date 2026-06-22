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
    private ?string $img = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $imgColor = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $seq;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: Style::class, cascade: ['persist'], inversedBy: 'styleImgs')]
    #[ORM\JoinColumn(name: 'style_id', referencedColumnName: 'id', nullable: false)]
    private Style $style;

    private ?UploadedFile $imgFile = null;

    private ?UploadedFile $imgColorFile = null;

    public function getId(): int {
        return $this->id;
    }

    public function setImg(?string $img): void {
        $this->img = $img;
    }

    public function getImg(): ?string {
        return $this->img;
    }

    public function setImgColor(?string $imgColor): void {
        $this->imgColor = $imgColor;
    }

    public function getImgColor(): ?string {
        return $this->imgColor;
    }

    public function setSeq(int $seq): void {
        $this->seq = $seq;
    }

    public function getSeq(): int {
        return $this->seq;
    }

    public function setCreated(\DateTime $created): void {
        $this->created = $created;
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function setModified(?\DateTime $modified): void {
        $this->modified = $modified;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function setStyle(?Style $style): void {
        $this->style = $style;
    }

    public function getStyle(): Style {
        return $this->style;
    }

    public function setImgFile(?UploadedFile $imgFile): void {
        $this->imgFile = $imgFile;
        $this->refreshUpdated();
    }

    public function getImgFile(): ?UploadedFile {
        return $this->imgFile;
    }

    public function uploadImgFile(): void {
        if (!$this->getImgFile() instanceof UploadedFile) {
            return;
        }

        $style = $this->getStyle();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'style_' . $style->getId() . '_img_' . md5(uniqid('', true)) . '.' . $extension;
        $this->getImgFile()->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->setImg(self::IMG_FOLDER . $fileName);
        $this->setImgFile(null);
    }

    public function setImgColorFile(?UploadedFile $imgColorFile): void {
        $this->imgColorFile = $imgColorFile;
        $this->refreshUpdated();
    }

    public function getImgColorFile(): ?UploadedFile {
        return $this->imgColorFile;
    }

    public function uploadImgColorFile(): void {
        if (!$this->getImgColorFile() instanceof UploadedFile) {
            return;
        }

        $style = $this->getStyle();

        $extension = $this->getImgColorFile()->getClientOriginalExtension();
        $fileName = 'style_' . $style->getId() . '_img_color_' . md5(uniqid('', true)) . '.' . $extension;
        $this->getImgColorFile()->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->setImgColor(self::IMG_FOLDER . $fileName);
        $this->setImgColorFile(null);
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function lifecycleImgFileUpload(): void {
        $this->uploadImgFile();
        $this->uploadImgColorFile();
    }

    public function refreshUpdated(): void {
        $this->setModified(new \DateTime());
    }

    #[ORM\PostRemove]
    public function removeImage(): void {
        $img = $this->getImg();
        if (($img !== null) && file_exists(FileHelper::DIR_PUBLIC . $img)) {
            @unlink(FileHelper::DIR_PUBLIC . $img);
        }
        $imgColor = $this->getImgColor();
        if (($imgColor !== null) && file_exists(FileHelper::DIR_PUBLIC . $imgColor)) {
            @unlink(FileHelper::DIR_PUBLIC . $imgColor);
        }
    }
}
