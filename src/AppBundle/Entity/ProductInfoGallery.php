<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductInfoGallery
 *
 * @ORM\Table(name="product_info_gallery", indexes={@ORM\Index(name="ix__product_info_gallery__product_info_id", columns={"product_info_id"})})
 * @ORM\Entity
 */
class ProductInfoGallery
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
     * @var \AppBundle\Entity\ProductInfo
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductInfo", inversedBy="productInfoGalleries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_info_id", referencedColumnName="id")
     * })
     */
    private $productInfo;



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
     * Set img.
     *
     * @param string $img
     *
     * @return ProductInfoGallery
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img.
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set seq.
     *
     * @param int $seq
     *
     * @return ProductInfoGallery
     */
    public function setSeq($seq)
    {
        $this->seq = $seq;

        return $this;
    }

    /**
     * Get seq.
     *
     * @return int
     */
    public function getSeq()
    {
        return $this->seq;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return ProductInfoGallery
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
     * @return ProductInfoGallery
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
     * Set productInfo.
     *
     * @param \AppBundle\Entity\ProductInfo|null $productInfo
     *
     * @return ProductInfoGallery
     */
    public function setProductInfo(\AppBundle\Entity\ProductInfo $productInfo = null)
    {
        $this->productInfo = $productInfo;

        return $this;
    }

    /**
     * Get productInfo.
     *
     * @return \AppBundle\Entity\ProductInfo|null
     */
    public function getProductInfo()
    {
        return $this->productInfo;
    }
}
