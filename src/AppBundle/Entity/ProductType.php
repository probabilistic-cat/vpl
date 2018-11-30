<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="product_type", indexes={@ORM\Index(name="ix__product_type__product_id", columns={"product_id"})})
 * @ORM\Entity
 */
class ProductType
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
     * @ORM\Column(name="text", type="string", length=255, nullable=false)
     */
    private $text;

    /**
     * @var string|null
     *
     * @ORM\Column(name="img", type="text", length=65535, nullable=true)
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
     * @var \AppBundle\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="productTypes", cascade={"persist"})
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
     * @param string $text
     * @return ProductType
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string|null $img
     * @return ProductType
     */
    public function setImg($img = null)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param int $seq
     * @return ProductType
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
     * @return ProductType
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
     * @return ProductType
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
     * @param \AppBundle\Entity\Product|null $product
     * @return ProductType
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

    public function __toString()
    {
        return get_class($this);
    }
}
