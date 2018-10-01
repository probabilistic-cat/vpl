<?php

namespace AppBundle\Entity;

/**
 * Property
 */
class Property
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
     * @var \DateTime
     */
    private $created = '2000-01-01 00:00:00';

    /**
     * @var \DateTime|null
     */
    private $modified;

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
     * Set name.
     *
     * @param string $name
     *
     * @return Property
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
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Property
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
     * @return Property
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
     * Add product.
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Property
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
