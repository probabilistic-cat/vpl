<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoryProperty
 *
 * @ORM\Table(name="category_property", indexes={@ORM\Index(name="ix__category_property__category_id", columns={"category_id"}), @ORM\Index(name="ix__category_property__property_id", columns={"property_id"})})
 * @ORM\Entity
 */
class CategoryProperty
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
     * @var int
     *
     * @ORM\Column(name="seq", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $seq;

    /**
     * @var int
     *
     * @ORM\Column(name="layer", type="smallint", nullable=false, options={"default"="1","unsigned"=true})
     */
    private $layer = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $active = '1';

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
     * @var \AppBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \AppBundle\Entity\Property
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Property")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     * })
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
