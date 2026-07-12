<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\TimestampFields;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
#[ORM\Table(name: 'property_item')]
#[ORM\Index(name: 'ix__property_item__property_set_id', columns: ['property_set_id'])]
class PropertyItem extends BaseEntity
{
    use TimestampFields;

    public const string IMAGE_FOLDER = 'img/property_item';
    public const string IMAGE_NAME_PREFIX = 'property_item';

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

    #[\Override]
    public function getImagePaths(): array {
        return [$this->img];
    }
}
