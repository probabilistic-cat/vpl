<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductInfo
 *
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductInfoLocation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_info_location_code", referencedColumnName="code")
     * })
     */
    private $productInfoLocationCode;

    /**
     * @var \AppBundle\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return ProductInfo
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set text.
     *
     * @param string|null $text
     *
     * @return ProductInfo
     */
    public function setText($text = null)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text.
     *
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set seq.
     *
     * @param int $seq
     *
     * @return ProductInfo
     */
    public function setSeq($seq)
    {
        $this->seq = $seq;

        return $this;
    }

    /**
     * Get seq.
     *
     * @return int
     */
    public function getSeq()
    {
        return $this->seq;
    }

    /**
     * Set isGallery.
     *
     * @param bool $isGallery
     *
     * @return ProductInfo
     */
    public function setIsGallery($isGallery)
    {
        $this->isGallery = $isGallery;

        return $this;
    }

    /**
     * Get isGallery.
     *
     * @return bool
     */
    public function getIsGallery()
    {
        return $this->isGallery;
    }

    /**
     * @return bool
     */
    public function isGallery()
    {
        return $this->isGallery;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return ProductInfo
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modified.
     *
     * @param \DateTime|null $modified
     *
     * @return ProductInfo
     */
    public function setModified($modified = null)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified.
     *
     * @return \DateTime|null
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set productInfoLocationCode.
     *
     * @param \AppBundle\Entity\ProductInfoLocation|null $productInfoLocationCode
     *
     * @return ProductInfo
     */
    public function setProductInfoLocationCode(\AppBundle\Entity\ProductInfoLocation $productInfoLocationCode = null)
    {
        $this->productInfoLocationCode = $productInfoLocationCode;

        return $this;
    }

    /**
     * Get productInfoLocationCode.
     *
     * @return \AppBundle\Entity\ProductInfoLocation|null
     */
    public function getProductInfoLocationCode()
    {
        return $this->productInfoLocationCode;
    }

    /**
     * Set product.
     *
     * @param \AppBundle\Entity\Product|null $product
     *
     * @return ProductInfo
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return \AppBundle\Entity\Product|null
     */
    public function getProduct()
    {
        return $this->product;
    }
}
