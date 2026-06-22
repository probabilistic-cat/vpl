<?php

declare(strict_types=1);

namespace App\Entity;

use App\Helper\FileHelper;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'product')]
#[ORM\Index(name: 'ix__product__subcategory_id', columns: ['subcategory_id'])]
#[ORM\HasLifecycleCallbacks]
class Product
{
    private const string IMG_FOLDER = 'img/product/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

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

    #[ORM\Column(options: ['default' => 'Kammern (Rahmen)'])]
    public string $chambersName = 'Kammern (Rahmen)';

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: Subcategory::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'subcategory_id', referencedColumnName: 'id', nullable: false)]
    public Subcategory $subcategory;

    /** @var Collection<ProductType> */
    #[ORM\OneToMany(targetEntity: ProductType::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private Collection $productTypes;

    /** @var Collection<ProductProperty> */
    #[ORM\OneToMany(targetEntity: ProductProperty::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['categoryProperty' => 'ASC', 'seq' => 'ASC'])]
    private Collection $productProperties;

    /** @var Collection<ProductInfoMiddle> */
    #[ORM\OneToMany(targetEntity: ProductInfoMiddle::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private Collection $productInfoMiddles;

    /** @var Collection<ProductInfoBottom> */
    #[ORM\OneToMany(targetEntity: ProductInfoBottom::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private Collection $productInfoBottoms;

    /** @var Collection<ProductManufacturer> */
    #[ORM\OneToMany(targetEntity: ProductManufacturer::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private Collection $productManufacturers;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modified = new \DateTime();
        }
    }

    public function __construct() {
        $this->productTypes = new ArrayCollection();
        $this->productProperties = new ArrayCollection();
        $this->productInfoMiddles = new ArrayCollection();
        $this->productInfoBottoms = new ArrayCollection();
        $this->productManufacturers = new ArrayCollection();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function addProductType(ProductType $productType): void {
        $productType->product = $this;
        $this->productTypes[] = $productType;
    }

    public function removeProductType(ProductType $productType): void {
        $this->productTypes->removeElement($productType);
    }

    /** @return Collection<ProductType> */
    public function getProductTypes(): Collection {
        return $this->productTypes;
    }

    public function addProductProperty(ProductProperty $productProperty): void {
        $productProperty->product = $this;
        $this->productProperties[] = $productProperty;
    }

    public function removeProductProperty(ProductProperty $productProperty): void {
        $this->productProperties->removeElement($productProperty);
    }

    /** @return Collection<ProductProperty> */
    public function getProductProperties(): Collection {
        return $this->productProperties;
    }

    public function addProductInfoMiddle(ProductInfoMiddle $productInfo): void {
        $productInfo->product = $this;
        $this->productInfoMiddles[] = $productInfo;
    }

    public function removeProductInfoMiddle(ProductInfoMiddle $productInfo): void {
        $this->productInfoMiddles->removeElement($productInfo);
    }

    /** @return Collection<ProductInfoMiddle> */
    public function getProductInfoMiddles(): Collection {
        return $this->productInfoMiddles;
    }

    public function addProductInfoBottom(ProductInfoBottom $productInfo): void {
        $productInfo->product = $this;
        $this->productInfoBottoms[] = $productInfo;
    }

    public function removeProductInfoBottom(ProductInfoBottom $productInfo): void {
        $this->productInfoBottoms->removeElement($productInfo);
    }

    /** @return Collection<ProductInfoBottom> */
    public function getProductInfoBottoms(): Collection {
        return $this->productInfoBottoms;
    }

    public function addProductManufacturer(ProductManufacturer $productManufacturer): void {
        $productManufacturer->product = $this;
        $this->productManufacturers[] = $productManufacturer;
    }

    public function removeProductManufacturer(ProductManufacturer $productManufacturer): void {
        $this->productManufacturers->removeElement($productManufacturer);
    }

    /** @return Collection<ProductManufacturer> */
    public function getProductManufacturers(): Collection {
        return $this->productManufacturers;
    }

    public function uploadImgFile(): void {
        if (!($this->imgFile instanceof UploadedFile)) {
            return;
        }

        $subcategory = $this->subcategory;
        $category = $subcategory->category;

        $extension = $this->imgFile->getClientOriginalExtension();
        $fileName = 'cat_' . $category->getId() . '_subcat_' . $subcategory->getId() . '_prod_' . md5(uniqid('', true))
            . '.' . $extension;
        $this->imgFile->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->img = self::IMG_FOLDER . $fileName;
        $this->imgFile = null;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function lifecycleImgFileUpload(): void {
        $this->uploadImgFile();
    }

    #[ORM\PostRemove]
    public function removeImage(): void {
        if (($this->img !== null) && file_exists(FileHelper::DIR_PUBLIC . $this->img)) {
            @unlink(FileHelper::DIR_PUBLIC . $this->img);
        }
    }
}
