<?php

declare(strict_types=1);

namespace App\Entity;

use App\Helper\FileHelper;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
#[ORM\Table(name: 'property_item')]
#[ORM\Index(name: 'ix__property_item__property_set_id', columns: ['property_set_id'])]
#[ORM\HasLifecycleCallbacks]
class PropertyItem implements \Stringable
{
    private const string IMG_FOLDER = 'img/property_item/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    public ?string $name = null;

    #[ORM\Column(type: Types::TEXT, length: 65535)]
    public string $img;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: PropertySet::class, cascade: ['persist'], inversedBy: 'propertyItems')]
    #[ORM\JoinColumn(name: 'property_set_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    public ?PropertySet $propertySet = null;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modified = new \DateTime();
        }
    }

    public function __clone() {
        $this->id = null;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function __toString(): string {
        return $this->name ?? 'PropertyItem';
    }

    public function uploadImgFile(): void {
        if (!($this->imgFile instanceof UploadedFile)) {
            return;
        }

        $fileName = $this->createFileName();

        $this->imgFile->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->img = self::IMG_FOLDER . $fileName;
        $this->imgFile = null;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function lifecycleImgFileUpload(): void {
        $this->uploadImgFile();
    }

    #[ORM\PostRemove]
    public function removeImage(): void {
        if (file_exists(FileHelper::DIR_PUBLIC . $this->img)) {
            @unlink(FileHelper::DIR_PUBLIC . $this->img);
        }
    }

    private function createFileName(): string {
        $extension = ($this->imgFile instanceof UploadedFile)
            ? $this->imgFile->getClientOriginalExtension()
            : pathinfo($this->img, PATHINFO_EXTENSION);

        return 'propitem_' . md5(uniqid('', true)) . '.' . $extension;
    }

    /** After clone and adding to property set */
    public function afterClone(): void {
        $cloneFileName = self::IMG_FOLDER . $this->createFileName();
        $originFileName = $this->img;

        try {
            copy($originFileName, $cloneFileName);
        } catch (\Exception) {
        }

        $this->img = $cloneFileName;
    }
}
