<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
#[ORM\Table(name: 'product_property')]
#[ORM\Index(name: 'ix__product_property__product_id', columns: ['product_id'])]
#[ORM\Index(name: 'ix__product_property__category_property_id', columns: ['category_property_id'])]
#[ORM\Index(name: 'ix__prod_prop_set__property_set_id', columns: ['property_set_id'])]
class ProductProperty extends BaseEntity implements \Stringable
{
    use IdField;
    use TimestampFields;

    public const string IMAGE_FOLDER = 'img/product_property';
    public const string IMAGE_NAME_PREFIX = 'product_property';

    #[ORM\Column(nullable: true)]
    public ?string $name = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $img = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\ManyToOne(targetEntity: CategoryProperty::class, inversedBy: 'productProperties')]
    #[ORM\JoinColumn(name: 'category_property_id', referencedColumnName: 'id', nullable: false)]
    public CategoryProperty $categoryProperty;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productProperties')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false)]
    public Product $product;

    #[ORM\ManyToOne(targetEntity: PropertySet::class, inversedBy: 'productProperties')]
    #[ORM\JoinColumn(name: 'property_set_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    public ?PropertySet $propertySet = null;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modifiedNow();
        }
    }

    public function __toString(): string {
        return 'ProductProperty';
    }

    #[\Override]
    public function getImagePaths(): array {
        return [$this->img];
    }
}
