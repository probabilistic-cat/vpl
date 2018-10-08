<?php

namespace AppBundle\Entity;

/**
 * CategoryProperty
 */
class CategoryProperty
{
    /**
     * @var int
     */
    private $id;

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
     * @var \AppBundle\Entity\Category
     */
    private $category;

    /**
     * @var \AppBundle\Entity\Property
     */
    private $property;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $product;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set seq.
     *
     * @param int $seq
     *
     * @return CategoryProperty
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
     * @return CategoryProperty
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
     * @return CategoryProperty
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
     * Set category.
     *
     * @param \AppBundle\Entity\Category|null $category
     *
     * @return CategoryProperty
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return \AppBundle\Entity\Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set property.
     *
     * @param \AppBundle\Entity\Property|null $property
     *
     * @return CategoryProperty
     */
    public function setProperty(\AppBundle\Entity\Property $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property.
     *
     * @return \AppBundle\Entity\Property|null
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Add product.
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return CategoryProperty
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $this->product[] = $product;

        return $this;
    }

    /**
     * Remove product.
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        return $this->product->removeElement($product);
    }

    /**
     * Get product.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduct()
    {
        return $this->product;
    }
}
