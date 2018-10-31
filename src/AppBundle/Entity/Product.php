<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product", indexes={@ORM\Index(name="ix__product__subcategory_id", columns={"subcategory_id"})})
 * @ORM\Entity
 */
class Product
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
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description_full", type="text", length=65535, nullable=true)
     */
    private $descriptionFull;

    /**
     * @var string|null
     *
     * @ORM\Column(name="img", type="text", length=65535, nullable=true)
     */
    private $img;

    /**
     * @var int
     *
     * @ORM\Column(name="seals", type="smallint", nullable=false, options={"default"="1","unsigned"=true})
     */
    private $seals = '1';

    /**
     * @var int
     *
     * @ORM\Column(name="chambers", type="smallint", nullable=false, options={"default"="1","unsigned"=true})
     */
    private $chambers = '1';

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
     * @var \AppBundle\Entity\Subcategory
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Subcategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id")
     * })
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
