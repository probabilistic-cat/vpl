<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'product_manufacturer')]
#[ORM\Index(name: 'ix__product_manuf__manufacturer_id', columns: ['manufacturer_id'])]
#[ORM\Index(name: 'ix__product_manuf__product_id', columns: ['product_id'])]
class ProductManufacturer
{
    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $seq;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: Manufacturer::class, cascade: ['persist'], inversedBy: 'productManufacturers')]
    #[ORM\JoinColumn(name: 'manufacturer_id', referencedColumnName: 'id', nullable: false)]
    private Manufacturer $manufacturer;

    #[ORM\ManyToOne(targetEntity: Product::class, cascade: ['persist'], inversedBy: 'productManufacturers')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false)]
    private Product $product;

    public function getId(): int {
        return $this->id;
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

    public function setManufacturer(?Manufacturer $manufacturer = null): self {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getManufacturer(): Manufacturer {
        return $this->manufacturer;
    }

    public function setProduct(?Product $product = null): self {
        $this->product = $product;

        return $this;
    }

    public function getProduct(): Product {
        return $this->product;
    }
}
