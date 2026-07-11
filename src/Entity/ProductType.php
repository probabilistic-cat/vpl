<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
#[ORM\Table(name: 'product_type')]
#[ORM\Index(name: 'ix__product_type__product_id', columns: ['product_id'])]
class ProductType extends BaseEntity implements \Stringable
{
    use IdField;
    use TimestampFields;

    public const string IMAGE_FOLDER = 'img/product_type';
    public const string IMAGE_NAME_PREFIX = 'product_type';

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

    #[\Override]
    public function getImagePaths(): array {
        return [$this->img];
    }
}
