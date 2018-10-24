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
     * @var int
     */
    private $layer = '1';

    /**
     * @var bool
     */
    private $active = '1';

    /**
     * @var \DateTime
     */
    private $created;

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
     * Set layer.
     *
     * @param int $layer
     *
     * @return CategoryProperty
     */
    public function setLayer($layer)
    {
        $this->layer = $layer;

        return $this;
    }

    /**
     * Get layer.
     *
     * @return int
     */
    public function getLayer()
    {
        return $this->layer;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return CategoryProperty
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
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
}
