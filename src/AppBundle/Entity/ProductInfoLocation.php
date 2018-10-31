<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="product_info_location")
 * @ORM\Entity
 */
class ProductInfoLocation
{
    const CODE_BOTTOM = 'bottom';
    const CODE_MIDDLE = 'middle';

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=32)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $code;

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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductInfo", mappedBy="productInfoLocation")
     */
    private $productInfos;



    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param \DateTime $created
     * @return ProductInfoLocation
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
     * @return ProductInfoLocation
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
     * @param \AppBundle\Entity\ProductInfo $productInfo
     * @return ProductInfoLocation
     */
    public function addProductInfo(\AppBundle\Entity\ProductInfo $productInfo)
    {
        $this->productInfos[] = $productInfo;
        return $this;
    }

    /**
     * @param \AppBundle\Entity\ProductType $productType
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductInfo(\AppBundle\Entity\ProductInfo $productInfo)
    {
        return $this->productInfos->removeElement($productInfo);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductInfos()
    {
        return $this->productInfos;
    }
}
