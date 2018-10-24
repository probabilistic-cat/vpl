<?php

namespace AppBundle\Entity;

/**
 * ProductInfo
 */
class ProductInfo
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $text;

    /**
     * @var int
     */
    private $seq;

    /**
     * @var bool
     */
    private $isGallery = '0';

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime|null
     */
    private $modified;

    /**
     * @var \AppBundle\Entity\ProductInfoLocation
     */
    private $productInfoLocationCode;

    /**
     * @var \AppBundle\Entity\Product
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
