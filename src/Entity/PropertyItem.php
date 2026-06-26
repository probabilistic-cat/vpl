<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\TimestampFields;
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
    use TimestampFields;

    private const string IMG_FOLDER = 'img/property_item/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private(set) ?int $id = null;

    #[ORM\Column(nullable: true)]
    public ?string $name = null;

    #[ORM\Column(type: Types::TEXT, length: 65535)]
    public string $img;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\ManyToOne(targetEntity: PropertySet::class, inversedBy: 'propertyItems')]
    #[ORM\JoinColumn(name: 'property_set_id', referencedColumnName: 'id', nullable: true)]
    public PropertySet $propertySet;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modified = new \DateTime();
        }
    }

    public function __clone() {
        $this->id = null;
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
        copy(FileHelper::DIR_PUBLIC . $originFileName, FileHelper::DIR_PUBLIC . $cloneFileName);
        $this->img = $cloneFileName;
    }
}
