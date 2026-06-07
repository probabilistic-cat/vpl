<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="category_property", indexes={@ORM\Index(name="ix__category_property__category_id", columns={"category_id"}), @ORM\Index(name="ix__category_property__property_id", columns={"property_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\CategoryPropertyRepository")
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
     * @var \App\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="categoryProperties", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \App\Entity\Property
     *
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="categoryProperties")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     * })
     */
    private $property;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProductProperty", mappedBy="categoryProperty")
     * @ORM\OrderBy({"seq" = "ASC"})
     */
    private $productProperties;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productProperties = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $seq
     * @return CategoryProperty
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
     * @param int $layer
     * @return CategoryProperty
     */
    public function setLayer($layer)
    {
        $this->layer = $layer;

        return $this;
    }

    /**
     * @return int
     */
    public function getLayer()
    {
        return $this->layer;
    }

    /**
     * @param bool $active
     * @return CategoryProperty
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param \DateTime $created
     * @return CategoryProperty
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
     * @return CategoryProperty
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
     * @param \App\Entity\Category|null $category
     * @return CategoryProperty
     */
    public function setCategory(\App\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return \App\Entity\Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param \App\Entity\Property|null $property
     * @return CategoryProperty
     */
    public function setProperty(\App\Entity\Property $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * @return \App\Entity\Property|null
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param \App\Entity\ProductProperty $productProperty
     * @return CategoryProperty
     */
    public function addProductProperty(\App\Entity\ProductProperty $productProperty)
    {
        $this->productProperties[] = $productProperty;
        return $this;
    }

    /**
     * @param \App\Entity\ProductProperty $productProperty
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductProperty(\App\Entity\ProductProperty $productProperty)
    {
        return $this->productProperties->removeElement($productProperty);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductProperties()
    {
        return $this->productProperties;
    }
}
