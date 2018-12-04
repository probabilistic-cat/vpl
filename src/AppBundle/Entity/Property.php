<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="property")
 * @ORM\Entity
 */
class Property
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CategoryProperty", mappedBy="property")
     */
    private $categoryProperties;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return Property
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
     * @return Property
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
     * @return Property
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
     * @param \AppBundle\Entity\CategoryProperty $categoryProperty
     * @return Property
     */
    public function addCategoryProperty(\AppBundle\Entity\CategoryProperty $categoryProperty)
    {
        $this->categoryProperties[] = $categoryProperty;

        return $this;
    }

    /**
     * @param \AppBundle\Entity\CategoryProperty $categoryProperty
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCategoryProperty(\AppBundle\Entity\CategoryProperty $categoryProperty)
    {
        return $this->categoryProperties->removeElement($categoryProperty);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoryProperties()
    {
        return $this->categoryProperties;
    }

    public function __toString()
    {
        return $this->name;
    }
}
