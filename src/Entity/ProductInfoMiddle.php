<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'product_info_middle')]
#[ORM\Index(name: 'ix__product_info_m__product_id', columns: ['product_id'])]
#[ORM\HasLifecycleCallbacks]
class ProductInfoMiddle
{
    use IdField;
    use TimestampFields;

    #[ORM\Column(nullable: true)]
    public ?string $name = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $text = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\Column]
    private(set) bool $isGallery;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productInfoMiddles')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false)]
    public Product $product;

    /** @var Collection<ProductInfoMiddleGallery> */
    #[ORM\OneToMany(targetEntity: ProductInfoMiddleGallery::class, mappedBy: 'productInfoMiddle', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private(set) Collection $productInfoMiddleGalleries;

    public function __construct() {
        $this->productInfoMiddleGalleries = new ArrayCollection();
    }

    public function addProductInfoMiddleGallery(ProductInfoMiddleGallery $productInfoGallery): void {
        if (!$this->productInfoMiddleGalleries->contains($productInfoGallery)) {
            $productInfoGallery->productInfoMiddle = $this;
            $this->productInfoMiddleGalleries->add($productInfoGallery);
        }
    }

    public function removeProductInfoMiddleGallery(ProductInfoMiddleGallery $productInfoGallery): void {
        $this->productInfoMiddleGalleries->removeElement($productInfoGallery);
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function prePersistAndUpdate(): void {
        $this->isGallery = $this->productInfoMiddleGalleries->count() > 0;
    }
}
