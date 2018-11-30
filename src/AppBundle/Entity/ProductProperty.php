<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="product_property", indexes={@ORM\Index(name="ix__product_property__product_id", columns={"product_id"}), @ORM\Index(name="ix__product_property__category_property_id", columns={"category_property_id"})})
 * @ORM\Entity
 */
class ProductProperty
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
     * @ORM\Column(name="img", type="text", length=65535, nullable=false)
     */
    private $img;

    /**
     * @var int
     *
     * @ORM\Column(name="seq", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $seq;

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
     * @var \AppBundle\Entity\CategoryProperty
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CategoryProperty", inversedBy="productProperties")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_property_id", referencedColumnName="id")
     * })
     */
    private $categoryProperty;

    /**
     * @var \AppBundle\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="productProperties", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $img
     * @return ProductProperty
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param int $seq
     * @return ProductProperty
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
     * @param \DateTime $created
     * @return ProductProperty
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
     * @return ProductProperty
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
     * @param \AppBundle\Entity\CategoryProperty|null $categoryProperty
     * @return ProductProperty
     */
    public function setCategoryProperty(\AppBundle\Entity\CategoryProperty $categoryProperty = null)
    {
        $this->categoryProperty = $categoryProperty;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\CategoryProperty|null
     */
    public function getCategoryProperty()
    {
        return $this->categoryProperty;
    }

    /**
     * @param \AppBundle\Entity\Product|null $product
     * @return ProductProperty
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Product|null
     */
    public function getProduct()
    {
        return $this->product;
    }
}
