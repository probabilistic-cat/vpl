<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="product_info", indexes={@ORM\Index(name="ix__product_info__product_id", columns={"product_id"}), @ORM\Index(name="ix__prod_inf__prod_inf_loc_code", columns={"product_info_location_code"})})
 * @ORM\Entity
 */
class ProductInfo
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
     * @ORM\Column(name="text", type="text", length=65535, nullable=true)
     */
    private $text;

    /**
     * @var int
     *
     * @ORM\Column(name="seq", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $seq;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_gallery", type="boolean", nullable=false)
     */
    private $isGallery = '0';

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
     * @var \AppBundle\Entity\ProductInfoLocation
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductInfoLocation", inversedBy="productInfos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_info_location_code", referencedColumnName="code")
     * })
     */
    private $productInfoLocation;

    /**
     * @var \AppBundle\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="productInfos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductInfoGallery", mappedBy="productInfo")
     */
    private $productInfoGalleries;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return ProductInfo
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
     * @param string|null $text
     * @return ProductInfo
     */
    public function setText($text = null)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param int $seq
     * @return ProductInfo
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
     * @param bool $gallery
     * @return ProductInfo
     */
    public function setGallery($gallery)
    {
        $this->isGallery = $gallery;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGallery()
    {
        return $this->isGallery;
    }

    /**
     * @param \DateTime $created
     * @return ProductInfo
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
     * @return ProductInfo
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
     * @param \AppBundle\Entity\ProductInfoLocation|null $productInfoLocation
     * @return ProductInfo
     */
    public function setProductInfoLocationCode(\AppBundle\Entity\ProductInfoLocation $productInfoLocation = null)
    {
        $this->productInfoLocation = $productInfoLocation;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\ProductInfoLocation|null
     */
    public function getProductInfoLocation()
    {
        return $this->productInfoLocation;
    }

    /**
     * @param \AppBundle\Entity\Product|null $product
     * @return ProductInfo
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Product|null
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param \AppBundle\Entity\ProductInfoGallery $productInfoGallery
     * @return ProductInfo
     */
    public function addProductInfoGallery(\AppBundle\Entity\ProductInfoGallery $productInfoGallery)
    {
        $this->productInfoGalleries[] = $productInfoGallery;
        return $this;
    }

    /**
     * @param \AppBundle\Entity\ProductInfoGallery $productInfoGallery
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductInfoGallery(\AppBundle\Entity\ProductInfoGallery $productInfoGallery)
    {
        return $this->productInfoGalleries->removeElement($productInfoGallery);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductInfoGalleries()
    {
        return $this->productInfoGalleries;
    }
}
