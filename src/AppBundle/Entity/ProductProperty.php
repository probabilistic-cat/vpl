<?php

namespace AppBundle\Entity;

/**
 * ProductProperty
 */
class ProductProperty
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
    private $created = '2000-01-01 00:00:00';

    /**
     * @var \DateTime|null
     */
    private $modified;

    /**
     * @var \AppBundle\Entity\CategoryProperty
     */
    private $categoryProperty;

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
     * Set img.
     *
     * @param string $img
     *
     * @return ProductProperty
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
     * @return ProductProperty
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
     * @return ProductProperty
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
     * @return ProductProperty
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
     * Set categoryProperty.
     *
     * @param \AppBundle\Entity\CategoryProperty|null $categoryProperty
     *
     * @return ProductProperty
     */
    public function setCategoryProperty(\AppBundle\Entity\CategoryProperty $categoryProperty = null)
    {
        $this->categoryProperty = $categoryProperty;

        return $this;
    }

    /**
     * Get categoryProperty.
     *
     * @return \AppBundle\Entity\CategoryProperty|null
     */
    public function getCategoryProperty()
    {
        return $this->categoryProperty;
    }

    /**
     * Set product.
     *
     * @param \AppBundle\Entity\Product|null $product
     *
     * @return ProductProperty
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
