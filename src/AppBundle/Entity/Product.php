<?php

namespace AppBundle\Entity;

/**
 * Product
 */
class Product
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
    private $description;

    /**
     * @var string|null
     */
    private $descriptionFull;

    /**
     * @var string|null
     */
    private $img;

    /**
     * @var int
     */
    private $seals = '1';

    /**
     * @var int
     */
    private $chambers = '1';

    /**
     * @var \DateTime
     */
    private $created = '2000-01-01 00:00:00';

    /**
     * @var \DateTime|null
     */
    private $modified;

    /**
     * @var \AppBundle\Entity\Subcategory
     */
    private $subcategory;


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
     * @return Product
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
     * Set description.
     *
     * @param string|null $description
     *
     * @return Product
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set descriptionFull.
     *
     * @param string|null $descriptionFull
     *
     * @return Product
     */
    public function setDescriptionFull($descriptionFull = null)
    {
        $this->descriptionFull = $descriptionFull;

        return $this;
    }

    /**
     * Get descriptionFull.
     *
     * @return string|null
     */
    public function getDescriptionFull()
    {
        return $this->descriptionFull;
    }

    /**
     * Set img.
     *
     * @param string|null $img
     *
     * @return Product
     */
    public function setImg($img = null)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img.
     *
     * @return string|null
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set seals.
     *
     * @param int $seals
     *
     * @return Product
     */
    public function setSeals($seals)
    {
        $this->seals = $seals;

        return $this;
    }

    /**
     * Get seals.
     *
     * @return int
     */
    public function getSeals()
    {
        return $this->seals;
    }

    /**
     * Set chambers.
     *
     * @param int $chambers
     *
     * @return Product
     */
    public function setChambers($chambers)
    {
        $this->chambers = $chambers;

        return $this;
    }

    /**
     * Get chambers.
     *
     * @return int
     */
    public function getChambers()
    {
        return $this->chambers;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Product
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
     * @return Product
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
     * Set subcategory.
     *
     * @param \AppBundle\Entity\Subcategory|null $subcategory
     *
     * @return Product
     */
    public function setSubcategory(\AppBundle\Entity\Subcategory $subcategory = null)
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    /**
     * Get subcategory.
     *
     * @return \AppBundle\Entity\Subcategory|null
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }
}
