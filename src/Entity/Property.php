<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="property")
 * @ORM\Entity(repositoryClass="App\Repository\PropertyRepository")
 */
class Property
{
    public const NAME_BESCHREIBUNG = 'Beschreibung';
    public const NAME_FARBEPALETTE = 'Farbepalette';
    public const NAME_MODEL = 'Model';
    public const NAME_FARBE = 'Farbe';
    public const NAME_GLAS = 'Glas';
    public const NAME_GRIFF = 'Griff';

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
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="CategoryProperty", mappedBy="property")
     */
    private $categoryProperties;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="PropertySet", mappedBy="property")
     */
    private $propertySets;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categoryProperties = new ArrayCollection();
        $this->propertySets = new ArrayCollection();
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
     */
    public function setName($name): self
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
     */
    public function setCreated($created): self
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
     */
    public function setModified($modified = null): self
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

    public function addCategoryProperty(CategoryProperty $categoryProperty): self
    {
        $this->categoryProperties[] = $categoryProperty;

        return $this;
    }

    /**
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeCategoryProperty(CategoryProperty $categoryProperty)
    {
        return $this->categoryProperties->removeElement($categoryProperty);
    }

    /**
     * @return Collection
     */
    public function getCategoryProperties()
    {
        return $this->categoryProperties;
    }

    public function addPropertySet(PropertySet $propertySet): self
    {
        $this->propertySets[] = $propertySet;

        return $this;
    }

    /**
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removePropertySet(PropertySet $propertySet)
    {
        return $this->propertySets->removeElement($propertySet);
    }

    /**
     * @return Collection
     */
    public function getPropertySets()
    {
        return $this->propertySets;
    }

    public function __toString()
    {
        return $this->name ?? 'Property';
    }
}
