<?php

declare(strict_types=1);

namespace App\Entity;

use App\Helper\FileHelper;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity()]
#[ORM\Table(name: 'product_property')]
#[ORM\Index(name: 'ix__product_property__product_id', columns: ['product_id'])]
#[ORM\Index(name: 'ix__product_property__category_property_id', columns: ['category_property_id'])]
#[ORM\Index(name: 'ix__prod_prop_set__property_set_id', columns: ['property_set_id'])]
#[ORM\HasLifecycleCallbacks]
class ProductProperty implements \Stringable
{
    private const string IMG_FOLDER = 'img/product_property/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $img = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $seq;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: CategoryProperty::class, inversedBy: 'productProperties')]
    #[ORM\JoinColumn(name: 'category_property_id', referencedColumnName: 'id', nullable: false)]
    private CategoryProperty $categoryProperty;

    #[ORM\ManyToOne(targetEntity: Product::class, cascade: ['persist'], inversedBy: 'productProperties')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false)]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: PropertySet::class, cascade: ['persist'], inversedBy: 'productProperties')]
    #[ORM\JoinColumn(name: 'property_set_id', referencedColumnName: 'id', nullable: true)]
    private ?PropertySet $propertySet = null;

    private ?UploadedFile $imgFile = null;

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

    public function setImg(?string $img): self {
        $this->img = $img;

        return $this;
    }

    public function getImg(): ?string {
        return $this->img;
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

    public function setCategoryProperty(?CategoryProperty $categoryProperty = null): self {
        $this->categoryProperty = $categoryProperty;

        return $this;
    }

    public function getCategoryProperty(): CategoryProperty {
        return $this->categoryProperty;
    }

    public function setProduct(?Product $product = null): self {
        $this->product = $product;

        return $this;
    }

    public function getProduct(): Product {
        return $this->product;
    }

    public function setPropertySet(?PropertySet $propertySet = null): self {
        $this->propertySet = $propertySet;

        return $this;
    }

    public function getPropertySet(): ?PropertySet {
        return $this->propertySet;
    }

    public function __toString(): string {
        return 'ProductProperty';
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

        $product = $this->getProduct();
        $subcategory = $product->getSubcategory();
        $category = $subcategory->getCategory();
        $categoryProperty = $this->getCategoryProperty();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'cat_' . $category->getId() . '_subcat_' . $subcategory->getId() . '_prod_' . $product->getId()
            . '_cprop_' . $categoryProperty->getId() . '_pprop_' . md5(uniqid('', true)) . '.' . $extension;
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
        if (file_exists(FileHelper::DIR_PUBLIC . $img)) {
            @unlink(FileHelper::DIR_PUBLIC . $img);
        }
    }
}
