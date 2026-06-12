<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="property_set", indexes={@ORM\Index(name="ix__property_set__property_id", columns={"property_id"})})
 * @ORM\Entity()
 */
class PropertySet
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
     * @var \App\Entity\Property
     *
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="propertySets", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     * })
     */
    private $property;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="PropertyItem", mappedBy="propertySet", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $propertyItems;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProductProperty", mappedBy="propertySet")
     */
    private $productProperties;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->propertyItems = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productProperties = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Clone
     */
    public function __clone()
    {
        $this->id = null;

        foreach ($this->propertyItems as $propertyItem) {
            $clonedPropertyItem = clone ($propertyItem);
            $this->addPropertyItem($clonedPropertyItem);
            $clonedPropertyItem->afterClone();
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return PropertySet
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \DateTime $created
     * @return PropertySet
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
     * @return PropertySet
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
     * @param \App\Entity\Property|null $property
     * @return PropertySet
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
     * @param \App\Entity\PropertyItem $propertyItem
     * @return PropertySet
     */
    public function addPropertyItem(\App\Entity\PropertyItem $propertyItem)
    {
        $propertyItem->setPropertySet($this);
        $this->propertyItems[] = $propertyItem;

        return $this;
    }

    /**
     * @param \App\Entity\PropertyItem $propertyItem
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePropertyItem(\App\Entity\PropertyItem $propertyItem)
    {
        return $this->propertyItems->removeElement($propertyItem);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPropertyItems()
    {
        return $this->propertyItems;
    }

    /**
     * @param \App\Entity\ProductProperty $productProperty
     * @return PropertySet
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

    public function __toString()
    {
        return $this->name ?? 'PropertySet';
    }
}
