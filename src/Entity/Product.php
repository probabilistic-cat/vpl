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
    private string $name;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $descriptionFull = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $img = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $seals = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $chambers = null;

    #[ORM\Column(options: ['default' => 'Kammern (Rahmen)'])]
    private string $chambersName = 'Kammern (Rahmen)';

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $seq;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: Subcategory::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'subcategory_id', referencedColumnName: 'id', nullable: false)]
    private Subcategory $subcategory;

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

    private ?UploadedFile $imgFile = null;

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

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setDescription(?string $description = null): self {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescriptionFull(?string $descriptionFull = null): self {
        $this->descriptionFull = $descriptionFull;

        return $this;
    }

    public function getDescriptionFull(): ?string {
        return $this->descriptionFull;
    }

    public function setImg(?string $img = null): self {
        $this->img = $img;

        return $this;
    }

    public function getImg(): ?string {
        return $this->img;
    }

    public function setSeals(?string $seals = null): self {
        $this->seals = $seals;

        return $this;
    }

    public function getSeals(): ?string {
        return $this->seals;
    }

    public function setChambers(?string $chambers = null): self {
        $this->chambers = $chambers;

        return $this;
    }

    public function getChambers(): ?string {
        return $this->chambers;
    }

    public function setChambersName(string $chambersName): self {
        $this->chambersName = $chambersName;

        return $this;
    }

    public function getChambersName(): string {
        return $this->chambersName;
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

    public function setSubcategory(?Subcategory $subcategory = null): self {
        $this->subcategory = $subcategory;

        return $this;
    }

    public function getSubcategory(): Subcategory {
        return $this->subcategory;
    }

    public function addProductType(ProductType $productType): self {
        $productType->setProduct($this);
        $this->productTypes[] = $productType;

        return $this;
    }

    /** @return bool TRUE if this collection contained the specified element, FALSE otherwise */
    public function removeProductType(ProductType $productType) {
        return $this->productTypes->removeElement($productType);
    }

    /** @return Collection<ProductType> */
    public function getProductTypes(): Collection {
        return $this->productTypes;
    }

    public function addProductProperty(ProductProperty $productProperty): self {
        $productProperty->setProduct($this);
        $this->productProperties[] = $productProperty;
        return $this;
    }

    /** @return bool TRUE if this collection contained the specified element, FALSE otherwise */
    public function removeProductProperty(ProductProperty $productProperty) {
        return $this->productProperties->removeElement($productProperty);
    }

    /** @return Collection<ProductProperty> */
    public function getProductProperties(): Collection {
        return $this->productProperties;
    }

    public function addProductInfoMiddle(ProductInfoMiddle $productInfo): self {
        $productInfo->setProduct($this);
        $this->productInfoMiddles[] = $productInfo;

        return $this;
    }

    /** @return bool TRUE if this collection contained the specified element, FALSE otherwise */
    public function removeProductInfoMiddle(ProductInfoMiddle $productInfo) {
        return $this->productInfoMiddles->removeElement($productInfo);
    }

    /** @return Collection<ProductInfoMiddle> */
    public function getProductInfoMiddles(): Collection {
        return $this->productInfoMiddles;
    }

    public function addProductInfoBottom(ProductInfoBottom $productInfo): self {
        $productInfo->setProduct($this);
        $this->productInfoBottoms[] = $productInfo;

        return $this;
    }

    /** @return bool TRUE if this collection contained the specified element, FALSE otherwise */
    public function removeProductInfoBottom(ProductInfoBottom $productInfo) {
        return $this->productInfoBottoms->removeElement($productInfo);
    }

    /** @return Collection<ProductInfoBottom> */
    public function getProductInfoBottoms(): Collection {
        return $this->productInfoBottoms;
    }

    public function addProductManufacturer(ProductManufacturer $productManufacturer): self {
        $productManufacturer->setProduct($this);
        $this->productManufacturers[] = $productManufacturer;

        return $this;
    }

    /** @return bool TRUE if this collection contained the specified element, FALSE otherwise */
    public function removeProductManufacturer(ProductManufacturer $productManufacturer) {
        return $this->productManufacturers->removeElement($productManufacturer);
    }

    /** @return Collection<ProductManufacturer> */
    public function getProductManufacturers(): Collection {
        return $this->productManufacturers;
    }

    public function setImgFile(?UploadedFile $imgFile = null): self {
        $this->imgFile = $imgFile;
        $this->refreshUpdated();

        return $this;
    }

    public function getImgFile(): ?UploadedFile {
        return $this->imgFile;
    }

    public function uploadImgFile(): void {
        if (!$this->getImgFile() instanceof UploadedFile) {
            return;
        }

        $subcategory = $this->getSubcategory();
        $category = $subcategory->getCategory();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'cat_' . $category->getId() . '_subcat_' . $subcategory->getId() . '_prod_' . md5(uniqid('', true))
            . '.' . $extension;
        $this->getImgFile()->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->setImg(self::IMG_FOLDER . $fileName);
        $this->setImgFile(null);
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function lifecycleImgFileUpload(): void {
        $this->uploadImgFile();
    }

    public function refreshUpdated(): void {
        $this->setModified(new \DateTime());
    }

    #[ORM\PostRemove]
    public function removeImage(): void {
        $img = $this->getImg();
        if (($img !== null) && file_exists(FileHelper::DIR_PUBLIC . $img)) {
            @unlink(FileHelper::DIR_PUBLIC . $img);
        }
    }
}
