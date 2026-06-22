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

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescriptionFull(?string $descriptionFull): void {
        $this->descriptionFull = $descriptionFull;
    }

    public function getDescriptionFull(): ?string {
        return $this->descriptionFull;
    }

    public function setImg(?string $img): void {
        $this->img = $img;
    }

    public function getImg(): ?string {
        return $this->img;
    }

    public function setSeals(?string $seals): void {
        $this->seals = $seals;
    }

    public function getSeals(): ?string {
        return $this->seals;
    }

    public function setChambers(?string $chambers): void {
        $this->chambers = $chambers;
    }

    public function getChambers(): ?string {
        return $this->chambers;
    }

    public function setChambersName(string $chambersName): void {
        $this->chambersName = $chambersName;
    }

    public function getChambersName(): string {
        return $this->chambersName;
    }

    public function setSeq(int $seq): void {
        $this->seq = $seq;
    }

    public function getSeq(): int {
        return $this->seq;
    }

    public function setCreated(\DateTime $created): void {
        $this->created = $created;
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function setModified(?\DateTime $modified): void {
        $this->modified = $modified;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function setSubcategory(?Subcategory $subcategory): void {
        $this->subcategory = $subcategory;
    }

    public function getSubcategory(): Subcategory {
        return $this->subcategory;
    }

    public function addProductType(ProductType $productType): void {
        $productType->setProduct($this);
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
        $productProperty->setProduct($this);
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
        $productInfo->setProduct($this);
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
        $productInfo->setProduct($this);
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
        $productManufacturer->setProduct($this);
        $this->productManufacturers[] = $productManufacturer;
    }

    public function removeProductManufacturer(ProductManufacturer $productManufacturer): void {
        $this->productManufacturers->removeElement($productManufacturer);
    }

    /** @return Collection<ProductManufacturer> */
    public function getProductManufacturers(): Collection {
        return $this->productManufacturers;
    }

    public function setImgFile(?UploadedFile $imgFile): void {
        $this->imgFile = $imgFile;
        $this->refreshUpdated();
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
