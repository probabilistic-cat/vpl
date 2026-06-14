<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(name="layer", type="smallint", nullable=false, options={"default"="0","unsigned"=true})
     */
    private $layer = '0';

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
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="categoryProperties", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var Property
     *
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="categoryProperties")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     * })
     */
    private $property;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ProductProperty", mappedBy="categoryProperty")
     * @ORM\OrderBy({"seq" = "ASC"})
     */
    private $productProperties;

    /**
     * Constructor
     */
    public function __construct() {
        $this->productProperties = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $seq
     */
    public function setSeq($seq): self {
        $this->seq = $seq;

        return $this;
    }

    /**
     * @return int
     */
    public function getSeq() {
        return $this->seq;
    }

    /**
     * @param int $layer
     */
    public function setLayer($layer): self {
        $this->layer = $layer;

        return $this;
    }

    /**
     * @return int
     */
    public function getLayer() {
        return $this->layer;
    }

    /**
     * @param bool $active
     */
    public function setActive($active): self {
        $this->active = $active;

        return $this;
    }

    /**
     * @return bool
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created): self {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param \DateTime|null $modified
     */
    public function setModified($modified = null): self {
        $this->modified = $modified;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getModified() {
        return $this->modified;
    }

    public function setCategory(?Category $category = null): self {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory() {
        return $this->category;
    }

    public function setProperty(?Property $property = null): self {
        $this->property = $property;

        return $this;
    }

    /**
     * @return Property|null
     */
    public function getProperty() {
        return $this->property;
    }

    public function addProductProperty(ProductProperty $productProperty): self {
        $this->productProperties[] = $productProperty;
        return $this;
    }

    /**
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeProductProperty(ProductProperty $productProperty) {
        return $this->productProperties->removeElement($productProperty);
    }

    /**
     * @return Collection
     */
    public function getProductProperties() {
        return $this->productProperties;
    }
}
