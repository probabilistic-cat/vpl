<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="property_set", indexes={@ORM\Index(name="ix__property_set__property_id", columns={"property_id"})})
 * @ORM\Entity()
 */
class PropertySet implements \Stringable
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

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
     * @var Property
     *
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="propertySets", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     * })
     */
    private $property;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="PropertyItem", mappedBy="propertySet", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $propertyItems;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ProductProperty", mappedBy="propertySet")
     */
    private $productProperties;

    /**
     * Constructor
     */
    public function __construct() {
        $this->propertyItems = new ArrayCollection();
        $this->productProperties = new ArrayCollection();
    }

    /**
     * Clone
     */
    public function __clone() {
        $this->id = null;

        foreach ($this->propertyItems as $propertyItem) {
            $clonedPropertyItem = clone $propertyItem;
            $this->addPropertyItem($clonedPropertyItem);
            $clonedPropertyItem->afterClone();
        }
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name): self {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
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

    public function addPropertyItem(PropertyItem $propertyItem): self {
        $propertyItem->setPropertySet($this);
        $this->propertyItems[] = $propertyItem;

        return $this;
    }

    /**
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removePropertyItem(PropertyItem $propertyItem) {
        return $this->propertyItems->removeElement($propertyItem);
    }

    /**
     * @return Collection
     */
    public function getPropertyItems() {
        return $this->propertyItems;
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

    public function __toString(): string {
        return $this->name ?? 'PropertySet';
    }
}
