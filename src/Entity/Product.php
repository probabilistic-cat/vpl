<?php

namespace App\Entity;

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
     * @var \App\Entity\Subcategory
     *
     * @ORM\ManyToOne(targetEntity="Subcategory", inversedBy="products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id")
     * })
     */
    private $subcategory;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProductType", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"seq" = "ASC"})
     */
    private $productTypes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProductProperty", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"categoryProperty"="ASC", "seq"="ASC"})
     */
    private $productProperties;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProductInfoMiddle", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"seq"="ASC"})
     */
    private $productInfoMiddles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProductInfoBottom", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"seq"="ASC"})
     */
    private $productInfoBottoms;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProductManufacturer", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"seq" = "ASC"})
     */
    private $productManufacturers;

    /**
     * @var UploadedFile
     */
    private $imgFile;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productTypes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productProperties = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productInfoMiddles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productInfoBottoms = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productManufacturers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Product
     */
    public function setName($name)
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
     * @return Product
     */
    public function setDescription($description = null)
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
     * @return Product
     */
    public function setDescriptionFull($descriptionFull = null)
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
     * @return Product
     */
    public function setImg($img = null)
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
     * @return Product
     */
    public function setSeals($seals = null)
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
     * @return Product
     */
    public function setChambers($chambers = null)
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
     * @return Product
     */
    public function setChambersName($chambersName)
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
     * @return Product
     */
    public function setSeq($seq)
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
     * @return Product
     */
    public function setCreated($created)
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
     * @return Product
     */
    public function setModified($modified = null)
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
     * @param \App\Entity\Subcategory|null $subcategory
     * @return Product
     */
    public function setSubcategory(\App\Entity\Subcategory $subcategory = null)
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    /**
     * @return \App\Entity\Subcategory|null
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /**
     * @param \App\Entity\ProductType $productType
     * @return Product
     */
    public function addProductType(\App\Entity\ProductType $productType)
    {
        $productType->setProduct($this);
        $this->productTypes[] = $productType;

        return $this;
    }

    /**
     * @param \App\Entity\ProductType $productType
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductType(\App\Entity\ProductType $productType)
    {
        return $this->productTypes->removeElement($productType);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductTypes()
    {
        return $this->productTypes;
    }

    /**
     * @param \App\Entity\ProductProperty $productProperty
     * @return Product
     */
    public function addProductProperty(\App\Entity\ProductProperty $productProperty)
    {
        $productProperty->setProduct($this);
        $this->productProperties[] = $productProperty;
        return $this;
    }

    /**
     * @param \App\Entity\ProductProperty $productProperty
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductProperty(\App\Entity\ProductProperty $productProperty)
    {
        return $this->productProperties->removeElement($productProperty);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductProperties()
    {
        return $this->productProperties;
    }

    /**
     * @param \App\Entity\ProductInfoMiddle $productInfo
     * @return Product
     */
    public function addProductInfoMiddle(\App\Entity\ProductInfoMiddle $productInfo)
    {
        $productInfo->setProduct($this);
        $this->productInfoMiddles[] = $productInfo;

        return $this;
    }

    /**
     * @param \App\Entity\ProductInfoMiddle $productInfo
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductInfoMiddle(\App\Entity\ProductInfoMiddle $productInfo)
    {
        return $this->productInfoMiddles->removeElement($productInfo);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductInfoMiddles()
    {
        return $this->productInfoMiddles;
    }

    /**
     * @param \App\Entity\ProductInfoBottom $productInfo
     * @return Product
     */
    public function addProductInfoBottom(\App\Entity\ProductInfoBottom $productInfo)
    {
        $productInfo->setProduct($this);
        $this->productInfoBottoms[] = $productInfo;

        return $this;
    }

    /**
     * @param \App\Entity\ProductInfoBottom $productInfo
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductInfoBottom(\App\Entity\ProductInfoBottom $productInfo)
    {
        return $this->productInfoBottoms->removeElement($productInfo);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductInfoBottoms()
    {
        return $this->productInfoBottoms;
    }

    /**
     * @param \App\Entity\ProductManufacturer $productManufacturer
     * @return Product
     */
    public function addProductManufacturer(\App\Entity\ProductManufacturer $productManufacturer)
    {
        $productManufacturer->setProduct($this);
        $this->productManufacturers[] = $productManufacturer;

        return $this;
    }

    /**
     * @param \App\Entity\ProductManufacturer $productManufacturer
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductManufacturer(\App\Entity\ProductManufacturer $productManufacturer)
    {
        return $this->productManufacturers->removeElement($productManufacturer);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductManufacturers()
    {
        return $this->productManufacturers;
    }

    /**
     * @param UploadedFile $imgFile
     * @return Subategory
     */
    public function setImgFile(UploadedFile $imgFile = null)
    {
        $this->imgFile = $imgFile;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImgFile()
    {
        return $this->imgFile;
    }

    public function uploadImgFile()
    {
        if (null === $this->getImgFile()) {
            return;
        }

        $subcategory = $this->getSubcategory();
        $category = $subcategory->getCategory();
        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $productId = empty($this->getId()) ? $microTimeStamp : $this->getId();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'cat_' . $category->getId() . '_subcat_' . $subcategory->getId() . '_prod_' . $productId
            . '.' . $extension;
        $this->getImgFile()->move(self::IMG_FOLDER, $fileName);
        $this->setImg(self::IMG_FOLDER . $fileName);
        $this->setImgFile(null);
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function lifecycleImgFileUpload()
    {
        $this->uploadImgFile();
    }

    public function refreshUpdated()
    {
        $this->setModified(new \DateTime());
    }

    /**
     * @ORM\PostRemove
     */
    public function removeImage()
    {
        if (file_exists($this->getImg())) {
            unlink($this->getImg());
        }
    }
}
