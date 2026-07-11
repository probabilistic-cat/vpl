<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'product')]
#[ORM\Index(name: 'ix__product__subcategory_id', columns: ['subcategory_id'])]
class Product extends BaseEntity
{
    use IdField;
    use TimestampFields;

    private const string CHAMBERS_NAME_DEFAULT = 'Kammern (Rahmen)';

    public const string IMAGE_FOLDER = 'img/product';
    public const string IMAGE_NAME_PREFIX = 'product';

    #[ORM\Column]
    public string $name;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $descriptionFull = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $img = null;

    #[ORM\Column(length: 16, nullable: true)]
    public ?string $seals = null;

    #[ORM\Column(length: 16, nullable: true)]
    public ?string $chambers = null;

    #[ORM\Column(options: ['default' => self::CHAMBERS_NAME_DEFAULT])]
    public string $chambersName = self::CHAMBERS_NAME_DEFAULT;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\ManyToOne(targetEntity: Subcategory::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'subcategory_id', referencedColumnName: 'id', nullable: false)]
    public Subcategory $subcategory;

    /** @var Collection<ProductType> */
    #[ORM\OneToMany(targetEntity: ProductType::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private(set) Collection $productTypes;

    /** @var Collection<ProductProperty> */
    #[ORM\OneToMany(targetEntity: ProductProperty::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['categoryProperty' => 'ASC', 'seq' => 'ASC'])]
    private(set) Collection $productProperties;

    /** @var Collection<ProductInfoMiddle> */
    #[ORM\OneToMany(targetEntity: ProductInfoMiddle::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private(set) Collection $productInfoMiddles;

    /** @var Collection<ProductInfoBottom> */
    #[ORM\OneToMany(targetEntity: ProductInfoBottom::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private(set) Collection $productInfoBottoms;

    /** @var Collection<ProductManufacturer> */
    #[ORM\OneToMany(targetEntity: ProductManufacturer::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private(set) Collection $productManufacturers;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modifiedNow();
        }
    }

    public function __construct() {
        $this->productTypes = new ArrayCollection();
        $this->productProperties = new ArrayCollection();
        $this->productInfoMiddles = new ArrayCollection();
        $this->productInfoBottoms = new ArrayCollection();
        $this->productManufacturers = new ArrayCollection();
    }

    public function addProductType(ProductType $productType): void {
        if (!$this->productTypes->contains($productType)) {
            $productType->product = $this;
            $this->productTypes->add($productType);
        }
    }

    public function removeProductType(ProductType $productType): void {
        $this->productTypes->removeElement($productType);
    }

    public function addProductProperty(ProductProperty $productProperty): void {
        if (!$this->productProperties->contains($productProperty)) {
            $productProperty->product = $this;
            $this->productProperties->add($productProperty);
        }
    }

    public function removeProductProperty(ProductProperty $productProperty): void {
        $this->productProperties->removeElement($productProperty);
    }

    public function addProductInfoMiddle(ProductInfoMiddle $productInfo): void {
        if (!$this->productInfoMiddles->contains($productInfo)) {
            $productInfo->product = $this;
            $this->productInfoMiddles->add($productInfo);
        }
    }

    public function removeProductInfoMiddle(ProductInfoMiddle $productInfo): void {
        $this->productInfoMiddles->removeElement($productInfo);
    }

    public function addProductInfoBottom(ProductInfoBottom $productInfo): void {
        if (!$this->productInfoBottoms->contains($productInfo)) {
            $productInfo->product = $this;
            $this->productInfoBottoms->add($productInfo);
        }
    }

    public function removeProductInfoBottom(ProductInfoBottom $productInfo): void {
        $this->productInfoBottoms->removeElement($productInfo);
    }

    public function addProductManufacturer(ProductManufacturer $productManufacturer): void {
        if (!$this->productManufacturers->contains($productManufacturer)) {
            $productManufacturer->product = $this;
            $this->productManufacturers->add($productManufacturer);
        }
    }

    public function removeProductManufacturer(ProductManufacturer $productManufacturer): void {
        $this->productManufacturers->removeElement($productManufacturer);
    }

    #[\Override]
    public function getImagePaths(): array {
        return [$this->img];
    }
}
