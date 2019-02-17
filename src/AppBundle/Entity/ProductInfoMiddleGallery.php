<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * ProductInfoMiddleGallery
 *
 * @ORM\Table(name="product_info_middle_gallery", indexes={@ORM\Index(name="ix__product_info_m_gal__product_info_m_id", columns={"product_info_middle_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductInfoMiddleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ProductInfoMiddleGallery
{
    const IMG_FOLDER = 'img/product_gallery/';

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
     * @var \AppBundle\Entity\ProductInfoMiddle
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductInfoMiddle", inversedBy="productInfoMiddleGalleries", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_info_middle_id", referencedColumnName="id")
     * })
     */
    private $productInfoMiddle;

    /**
     * @var UploadedFile
     */
    private $imgFile;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $img
     * @return ProductInfoMiddleGallery
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
     * @return ProductInfoMiddleGallery
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
     * @return ProductInfoMiddleGallery
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
     * @return ProductInfoMiddleGallery
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
     * @param \AppBundle\Entity\ProductInfoMiddle|null $productInfoMiddle
     * @return ProductInfoMiddleGallery
     */
    public function setProductInfoMiddle(\AppBundle\Entity\ProductInfoMiddle $productInfoMiddle = null)
    {
        $this->productInfoMiddle = $productInfoMiddle;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\ProductInfoMiddle|null
     */
    public function getProductInfoMiddle()
    {
        return $this->productInfoMiddle;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Gallery';
    }


    /**
     * @param UploadedFile $imgFile
     * @return Category
     */
    public function setImgFile(UploadedFile $imgFile = null)
    {
        $this->imgFile = $imgFile;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImgFile()
    {
        return $this->imgFile;
    }

    public function uploadImgFile()
    {
        if (null === $this->getImgFile()) {
            return;
        }

        $info = $this->getProductInfoMiddle();
        $product = $info->getProduct();
        $subcategory = $product->getSubcategory();
        $category = $subcategory->getCategory();
        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $galId = empty($this->getId()) ? $microTimeStamp : $this->getId();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'cat_' . $category->getId() . '_subcat_' . $subcategory->getId() . '_prod_' . $product->getId()
            . '_info_' . $info->getId() . '_gal_' . $galId . '.' . $extension;
        $this->getImgFile()->move(self::IMG_FOLDER, $fileName);
        $this->setImg(self::IMG_FOLDER . $fileName);
        $this->setImgFile(null);
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function lifecycleImgFileUpload()
    {
        $this->uploadImgFile();
    }

    public function refreshUpdated()
    {
        $this->setModified(new \DateTime());
    }

    /**
     * @ORM\PostRemove
     */
    public function removeImage()
    {
        if (file_exists($this->getImg())) {
            unlink($this->getImg());
        }
    }
}
