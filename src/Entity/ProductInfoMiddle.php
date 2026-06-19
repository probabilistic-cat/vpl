<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductInfoMiddleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductInfoMiddleRepository::class)]
#[ORM\Table(name: 'product_info_middle')]
#[ORM\Index(name: 'ix__product_info_m__product_id', columns: ['product_id'])]
class ProductInfoMiddle
{
    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $text = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $seq;

    #[ORM\Column(options: ['default' => false])]
    private bool $isGallery = false;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: Product::class, cascade: ['persist'], inversedBy: 'productInfoMiddles')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false)]
    private Product $product;

    /** @var Collection<ProductInfoMiddleGallery> */
    #[ORM\OneToMany(targetEntity: ProductInfoMiddleGallery::class, mappedBy: 'productInfoMiddle', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private Collection $productInfoMiddleGalleries;

    public function __construct() {
        $this->productInfoMiddleGalleries = new ArrayCollection();
    }

    public function getId(): int {
        return $this->id;
    }

    public function setName(?string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setText(?string $text = null): self {
        $this->text = $text;

        return $this;
    }

    public function getText(): ?string {
        return $this->text;
    }

    public function setSeq(int $seq): self {
        $this->seq = $seq;

        return $this;
    }

    public function getSeq(): int {
        return $this->seq;
    }

    public function setIsGallery(bool $isGallery): self {
        $this->isGallery = $isGallery;

        return $this;
    }

    public function isGallery(): bool {
        return $this->isGallery;
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

    public function setProduct(?Product $product = null): self {
        $this->product = $product;

        return $this;
    }

    public function getProduct(): Product {
        return $this->product;
    }

    public function addProductInfoMiddleGallery(ProductInfoMiddleGallery $productInfoGallery): self {
        $productInfoGallery->setProductInfoMiddle($this);
        $this->productInfoMiddleGalleries[] = $productInfoGallery;
        return $this;
    }

    /** @return bool TRUE if this collection contained the specified element, FALSE otherwise */
    public function removeProductInfoMiddleGallery(ProductInfoMiddleGallery $productInfoGallery) {
        return $this->productInfoMiddleGalleries->removeElement($productInfoGallery);
    }

    /** @return Collection<ProductInfoMiddleGallery> */
    public function getProductInfoMiddleGalleries(): Collection {
        return $this->productInfoMiddleGalleries;
    }
}
