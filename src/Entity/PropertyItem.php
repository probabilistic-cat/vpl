<?php

declare(strict_types=1);

namespace App\Entity;

use App\Helper\FileHelper;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
#[ORM\Table(name: 'property_item')]
#[ORM\Index(columns: ['property_set_id'], name: 'ix__property_item__property_set_id')]
#[ORM\HasLifecycleCallbacks]
class PropertyItem implements \Stringable
{
    private const string IMG_FOLDER = 'img/property_item/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, length: 65535)]
    private string $img;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $seq;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: PropertySet::class, cascade: ['persist'], inversedBy: 'propertyItems')]
    #[ORM\JoinColumn(name: 'property_set_id', referencedColumnName: 'id', nullable: true)]
    private ?PropertySet $propertySet = null;

    private ?UploadedFile $imgFile = null;

    public function __clone() {
        $this->id = null;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setName(?string $name = null): self {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setImg(string $img): self {
        $this->img = $img;

        return $this;
    }

    public function getImg(): string {
        return $this->img;
    }

    public function setSeq(int $seq): self {
        $this->seq = $seq;

        return $this;
    }

    public function getSeq(): int {
        return $this->seq;
    }

    public function setCreated(\DateTime $created): self {
        $this->created = $created;

        return $this;
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function setModified(?\DateTime $modified = null): self {
        $this->modified = $modified;

        return $this;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function setPropertySet(?PropertySet $propertySet = null): self {
        $this->propertySet = $propertySet;

        return $this;
    }

    public function getPropertySet(): ?PropertySet {
        return $this->propertySet;
    }

    public function __toString(): string {
        return $this->name ?? 'PropertyItem';
    }

    public function setImgFile(?UploadedFile $imgFile = null): self {
        $this->imgFile = $imgFile;
        $this->refreshUpdated();

        return $this;
    }

    public function getImgFile(): ?UploadedFile {
        return $this->imgFile;
    }

    public function uploadImgFile(): void {
        if (!$this->getImgFile() instanceof UploadedFile) {
            return;
        }

        $fileName = $this->createFileName();

        $this->getImgFile()->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->setImg(self::IMG_FOLDER . $fileName);
        $this->setImgFile(null);
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function lifecycleImgFileUpload(): void {
        $this->uploadImgFile();
    }

    public function refreshUpdated(): void {
        $this->setModified(new \DateTime());
    }

    #[ORM\PostRemove]
    public function removeImage(): void {
        $img = $this->getImg();
        if (file_exists(FileHelper::DIR_PUBLIC . $img)) {
            @unlink(FileHelper::DIR_PUBLIC . $img);
        }
    }

    private function createFileName(): string {
        $extension = ($this->getImgFile() instanceof UploadedFile)
            ? $this->getImgFile()->getClientOriginalExtension()
            : pathinfo($this->getImg(), PATHINFO_EXTENSION);

        $fileName = 'propitem_' . md5(uniqid('', true)) . '.' . $extension;

        return $fileName;
    }

    /** After clone and adding to property set */
    public function afterClone(): void {
        $cloneFileName = self::IMG_FOLDER . $this->createFileName();
        $originFileName = $this->getImg();

        try {
            copy($originFileName, $cloneFileName);
        } catch (\Exception) {
        }

        $this->setImg($cloneFileName);
    }

    public function actualizeFileName(): void {
        $actualFileName = self::IMG_FOLDER . $this->createFileName();

        if (strcmp($actualFileName, $this->getImg()) !== 0) {
            rename($this->getImg(), $actualFileName);
            $this->setImg($actualFileName);
        }
    }
}
