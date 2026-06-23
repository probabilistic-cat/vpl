<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Field\IdField;
use App\Entity\Field\TimestampFields;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'product_manufacturer')]
#[ORM\Index(name: 'ix__product_manuf__manufacturer_id', columns: ['manufacturer_id'])]
#[ORM\Index(name: 'ix__product_manuf__product_id', columns: ['product_id'])]
class ProductManufacturer
{
    use IdField;
    use TimestampFields;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\ManyToOne(targetEntity: Manufacturer::class, cascade: ['persist'], inversedBy: 'productManufacturers')]
    #[ORM\JoinColumn(name: 'manufacturer_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    public Manufacturer $manufacturer;

    #[ORM\ManyToOne(targetEntity: Product::class, cascade: ['persist'], inversedBy: 'productManufacturers')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    public Product $product;
}
