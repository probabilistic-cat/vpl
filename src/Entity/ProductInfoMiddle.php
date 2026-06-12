<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductInfoMiddle
 *
 * @ORM\Table(name="product_info_middle", indexes={@ORM\Index(name="ix__product_info_m__product_id", columns={"product_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ProductInfoMiddleRepository")
 */
class ProductInfoMiddle
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="text", type="text", length=65535, nullable=true)
     */
    private $text;

    /**
     * @var int
     *
     * @ORM\Column(name="seq", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $seq;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_gallery", type="boolean", nullable=false)
     */
    private $isGallery = false;

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
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productInfoMiddles", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ProductInfoMiddleGallery", mappedBy="productInfoMiddle", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"seq" = "ASC"})
     */
    private $productInfoMiddleGalleries;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productInfoMiddleGalleries = new ArrayCollection();
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
     * @param string|null $text
     */
    public function setText($text = null): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param int $seq
     */
    public function setSeq($seq): self
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
     * @param bool $isGallery
     */
    public function setIsGallery($isGallery): self
    {
        $this->isGallery = $isGallery;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGallery()
    {
        return $this->isGallery;
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

    public function setProduct(Product $product = null): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct()
    {
        return $this->product;
    }

    public function addProductInfoMiddleGallery(ProductInfoMiddleGallery $productInfoGallery): self
    {
        $productInfoGallery->setProductInfoMiddle($this);
        $this->productInfoMiddleGalleries[] = $productInfoGallery;
        return $this;
    }

    /**
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeProductInfoMiddleGallery(ProductInfoMiddleGallery $productInfoGallery)
    {
        return $this->productInfoMiddleGalleries->removeElement($productInfoGallery);
    }

    /**
     * @return Collection
     */
    public function getProductInfoMiddleGalleries()
    {
        return $this->productInfoMiddleGalleries;
    }
}
