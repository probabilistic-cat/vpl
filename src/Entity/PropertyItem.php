<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\ImgFunctions;
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
    use ImgFunctions;
    use TimestampFields;

    private const string IMG_FOLDER_NAME = 'property_item';

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
            $this->modifiedNow();
        }
    }

    public function __clone() {
        $this->id = null;
    }

    public function __toString(): string {
        return $this->name ?? 'PropertyItem';
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function prePersistUpdateImg(): void {
        self::uploadImgFile($this->imgFile, self::IMG_FOLDER_NAME, function (string $img): void {
            $this->img = $img;
            $this->imgFile = null;
        });
    }

    #[ORM\PostRemove]
    public function postRemoveImg(): void {
        self::deleteImage($this->img);
    }

    /** After clone and adding to property set */
    public function afterClone(): void {
        $extension = pathinfo($this->img, PATHINFO_EXTENSION);
        $cloneFileName =
            FileHelper::getImgFolder() . self::IMG_FOLDER_NAME . '/' . self::getFileName($extension, self::IMG_FOLDER_NAME)
        ;
        $originFileName = $this->img;
        copy(FileHelper::DIR_PUBLIC . $originFileName, FileHelper::DIR_PUBLIC . $cloneFileName);
        $this->img = $cloneFileName;
    }
}
