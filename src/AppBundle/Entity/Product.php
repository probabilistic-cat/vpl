<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="product", indexes={@ORM\Index(name="ix__product__subcategory_id", columns={"subcategory_id"})})
 * @ORM\Entity
 */
class Product
{
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
     * @var int
     *
     * @ORM\Column(name="seals", type="smallint", nullable=false, options={"default"="1","unsigned"=true})
     */
    private $seals = '1';

    /**
     * @var int
     *
     * @ORM\Column(name="chambers", type="smallint", nullable=false, options={"default"="1","unsigned"=true})
     */
    private $chambers = '1';

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
     * @var \AppBundle\Entity\Subcategory
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Subcategory", inversedBy="products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id")
     * })
     */
    private $subcategory;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductType", mappedBy="product")
     */
    private $productTypes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductProperty", mappedBy="product")
     */
    private $productProperties;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductInfo", mappedBy="product")
     */
    private $productInfos;



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
     * @param int $seals
     * @return Product
     */
    public function setSeals($seals)
    {
        $this->seals = $seals;

        return $this;
    }

    /**
     * @return int
     */
    public function getSeals()
    {
        return $this->seals;
    }

    /**
     * @param int $chambers
     * @return Product
     */
    public function setChambers($chambers)
    {
        $this->chambers = $chambers;

        return $this;
    }

    /**
     * @return int
     */
    public function getChambers()
    {
        return $this->chambers;
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
     * @param \AppBundle\Entity\Subcategory|null $subcategory
     * @return Product
     */
    public function setSubcategory(\AppBundle\Entity\Subcategory $subcategory = null)
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Subcategory|null
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /**
     * @param \AppBundle\Entity\ProductType $productType
     * @return Product
     */
    public function addProductType(\AppBundle\Entity\ProductType $productType)
    {
        $this->productTypes[] = $productType;
        return $this;
    }

    /**
     * @param \AppBundle\Entity\ProductType $productType
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductType(\AppBundle\Entity\ProductType $productType)
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
     * @param \AppBundle\Entity\ProductProperty $productProperty
     * @return Product
     */
    public function addProductProperty(\AppBundle\Entity\ProductProperty $productProperty)
    {
        $this->productProperties[] = $productProperty;
        return $this;
    }

    /**
     * @param \AppBundle\Entity\ProductProperty $productProperty
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductProperty(\AppBundle\Entity\ProductProperty $productProperty)
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
     * @param \AppBundle\Entity\ProductInfo $productInfo
     * @return Product
     */
    public function addProductInfo(\AppBundle\Entity\ProductInfo $productInfo)
    {
        $this->productInfos[] = $productInfo;
        return $this;
    }

    /**
     * @param \AppBundle\Entity\ProductType $productType
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductInfo(\AppBundle\Entity\ProductInfo $productInfo)
    {
        return $this->productInfos->removeElement($productInfo);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductInfos()
    {
        return $this->productInfos;
    }
}
