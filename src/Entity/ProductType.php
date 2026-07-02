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
#[ORM\Table(name: 'product_type')]
#[ORM\Index(name: 'ix__product_type__product_id', columns: ['product_id'])]
#[ORM\HasLifecycleCallbacks]
class ProductType implements \Stringable
{
    use IdField;
    use ImgFunctions;
    use TimestampFields;

    private const string IMG_FOLDER_NAME = 'product_type';

    #[ORM\Column]
    public string $text;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $img = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productTypes')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false)]
    public Product $product;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modifiedNow();
        }
    }

    public function __toString(): string {
        return 'ProductType';
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
}
