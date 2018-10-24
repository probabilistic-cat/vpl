<?php

namespace AppBundle\Entity;

/**
 * ProductInfoGallery
 */
class ProductInfoGallery
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $img;

    /**
     * @var int
     */
    private $seq;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime|null
     */
    private $modified;

    /**
     * @var \AppBundle\Entity\ProductInfo
     */
    private $productInfo;


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
     * Set img.
     *
     * @param string $img
     *
     * @return ProductInfoGallery
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img.
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set seq.
     *
     * @param int $seq
     *
     * @return ProductInfoGallery
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
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return ProductInfoGallery
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
     * @return ProductInfoGallery
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
     * Set productInfo.
     *
     * @param \AppBundle\Entity\ProductInfo|null $productInfo
     *
     * @return ProductInfoGallery
     */
    public function setProductInfo(\AppBundle\Entity\ProductInfo $productInfo = null)
    {
        $this->productInfo = $productInfo;

        return $this;
    }

    /**
     * Get productInfo.
     *
     * @return \AppBundle\Entity\ProductInfo|null
     */
    public function getProductInfo()
    {
        return $this->productInfo;
    }
}
