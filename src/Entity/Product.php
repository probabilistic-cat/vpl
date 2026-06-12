<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Helper\FileHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="product", indexes={@ORM\Index(name="ix__product__subcategory_id", columns={"subcategory_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Product
{
    const IMG_FOLDER = 'img/product/';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description_full", type="text", length=65535, nullable=true)
     */
    private $descriptionFull;

    /**
     * @var string|null
     *
     * @ORM\Column(name="img", type="text", length=65535, nullable=true)
     */
    private $img;

    /**
     * @var string|null
     *
     * @ORM\Column(name="seals", type="string", length=16, nullable=true)
     */
    private $seals;

    /**
     * @var string|null
     *
     * @ORM\Column(name="chambers", type="string", length=16, nullable=true)
     */
    private $chambers;

    /**
     * @var string
     *
     * @ORM\Column(name="chambers_name", type="string", length=255, nullable=false)
     */
    private $chambersName = 'Kammern (Rahmen)';

    /**
     * @var int
     *
     * @ORM\Column(name="seq", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $seq;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false, options={"default"="2000-01-01 00:00:00"})
     */
    private $created;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @var Subcategory
     *
     * @ORM\ManyToOne(targetEntity="Subcategory", inversedBy="products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id")
     * })
     */
    private $subcategory;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ProductType", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"seq" = "ASC"})
     */
    private $productTypes;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ProductProperty", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"categoryProperty"="ASC", "seq"="ASC"})
     */
    private $productProperties;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ProductInfoMiddle", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"seq"="ASC"})
     */
    private $productInfoMiddles;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ProductInfoBottom", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"seq"="ASC"})
     */
    private $productInfoBottoms;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ProductManufacturer", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"seq" = "ASC"})
     */
    private $productManufacturers;

    private ?UploadedFile $imgFile = null;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productTypes = new ArrayCollection();
        $this->productProperties = new ArrayCollection();
        $this->productInfoMiddles = new ArrayCollection();
        $this->productInfoBottoms = new ArrayCollection();
        $this->productManufacturers = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $description
     */
    public function setDescription($description = null): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string|null $descriptionFull
     */
    public function setDescriptionFull($descriptionFull = null): self
    {
        $this->descriptionFull = $descriptionFull;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescriptionFull()
    {
        return $this->descriptionFull;
    }

    /**
     * @param string|null $img
     */
    public function setImg($img = null): self
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param string|null $seals
     */
    public function setSeals($seals = null): self
    {
        $this->seals = $seals;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSeals()
    {
        return $this->seals;
    }

    /**
     * @param string|null $chambers
     */
    public function setChambers($chambers = null): self
    {
        $this->chambers = $chambers;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getChambers()
    {
        return $this->chambers;
    }

    /**
     * @param string $chambersName
     */
    public function setChambersName($chambersName): self
    {
        $this->chambersName = $chambersName;

        return $this;
    }

    /**
     * @return string
     */
    public function getChambersName()
    {
        return $this->chambersName;
    }

    /**
     * @param int $seq
     */
    public function setSeq($seq): self
    {
        $this->seq = $seq;

        return $this;
    }

    /**
     * @return int
     */
    public function getSeq()
    {
        return $this->seq;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime|null $modified
     */
    public function setModified($modified = null): self
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param Subcategory|null $subcategory
     */
    public function setSubcategory(Subcategory $subcategory = null): self
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    /**
     * @return Subcategory|null
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    public function addProductType(ProductType $productType): self
    {
        $productType->setProduct($this);
        $this->productTypes[] = $productType;

        return $this;
    }

    /**
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductType(ProductType $productType)
    {
        return $this->productTypes->removeElement($productType);
    }

    /**
     * @return Collection
     */
    public function getProductTypes()
    {
        return $this->productTypes;
    }

    public function addProductProperty(ProductProperty $productProperty): self
    {
        $productProperty->setProduct($this);
        $this->productProperties[] = $productProperty;
        return $this;
    }

    /**
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductProperty(ProductProperty $productProperty)
    {
        return $this->productProperties->removeElement($productProperty);
    }

    /**
     * @return Collection
     */
    public function getProductProperties()
    {
        return $this->productProperties;
    }

    public function addProductInfoMiddle(ProductInfoMiddle $productInfo): self
    {
        $productInfo->setProduct($this);
        $this->productInfoMiddles[] = $productInfo;

        return $this;
    }

    /**
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductInfoMiddle(ProductInfoMiddle $productInfo)
    {
        return $this->productInfoMiddles->removeElement($productInfo);
    }

    /**
     * @return Collection
     */
    public function getProductInfoMiddles()
    {
        return $this->productInfoMiddles;
    }

    public function addProductInfoBottom(ProductInfoBottom $productInfo): self
    {
        $productInfo->setProduct($this);
        $this->productInfoBottoms[] = $productInfo;

        return $this;
    }

    /**
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductInfoBottom(ProductInfoBottom $productInfo)
    {
        return $this->productInfoBottoms->removeElement($productInfo);
    }

    /**
     * @return Collection
     */
    public function getProductInfoBottoms()
    {
        return $this->productInfoBottoms;
    }

    public function addProductManufacturer(ProductManufacturer $productManufacturer): self
    {
        $productManufacturer->setProduct($this);
        $this->productManufacturers[] = $productManufacturer;

        return $this;
    }

    /**
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductManufacturer(ProductManufacturer $productManufacturer)
    {
        return $this->productManufacturers->removeElement($productManufacturer);
    }

    /**
     * @return Collection
     */
    public function getProductManufacturers()
    {
        return $this->productManufacturers;
    }

    public function setImgFile(UploadedFile $imgFile = null): self
    {
        $this->imgFile = $imgFile;
        $this->refreshUpdated();

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImgFile(): ?UploadedFile
    {
        return $this->imgFile;
    }

    public function uploadImgFile(): void
    {
        if (!($this->getImgFile() instanceof UploadedFile)) {
            return;
        }

        $subcategory = $this->getSubcategory();
        $category = $subcategory->getCategory();
        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $productId = empty($this->getId()) ? $microTimeStamp : $this->getId();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'cat_' . $category->getId() . '_subcat_' . $subcategory->getId() . '_prod_' . $productId
            . '.' . $extension;
        $this->getImgFile()->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->setImg(self::IMG_FOLDER . $fileName);
        $this->setImgFile(null);
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function lifecycleImgFileUpload(): void
    {
        $this->uploadImgFile();
    }

    public function refreshUpdated(): void
    {
        $this->setModified(new \DateTime());
    }

    /**
     * @ORM\PostRemove
     */
    public function removeImage(): void
    {
        $img = $this->getImg();
        if (($img !== null) && file_exists(FileHelper::DIR_PUBLIC . $img)) {
            @unlink(FileHelper::DIR_PUBLIC . $img);
        }
    }
}
