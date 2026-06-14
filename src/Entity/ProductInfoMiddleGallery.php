<?php

declare(strict_types=1);

namespace App\Entity;

use App\Helper\FileHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * ProductInfoMiddleGallery
 *
 * @ORM\Table(name="product_info_middle_gallery", indexes={@ORM\Index(name="ix__product_info_m_gal__product_info_m_id", columns={"product_info_middle_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ProductInfoMiddleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ProductInfoMiddleGallery implements \Stringable
{
    private const IMG_FOLDER = 'img/product_gallery/';

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
     * @var ProductInfoMiddle
     *
     * @ORM\ManyToOne(targetEntity="ProductInfoMiddle", inversedBy="productInfoMiddleGalleries", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_info_middle_id", referencedColumnName="id")
     * })
     */
    private $productInfoMiddle;

    private ?UploadedFile $imgFile = null;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $img
     */
    public function setImg($img): self {
        $this->img = $img;

        return $this;
    }

    /**
     * @return string
     */
    public function getImg() {
        return $this->img;
    }

    /**
     * @param int $seq
     */
    public function setSeq($seq): self {
        $this->seq = $seq;

        return $this;
    }

    /**
     * @return int
     */
    public function getSeq() {
        return $this->seq;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created): self {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param \DateTime|null $modified
     */
    public function setModified($modified = null): self {
        $this->modified = $modified;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getModified() {
        return $this->modified;
    }

    public function setProductInfoMiddle(?ProductInfoMiddle $productInfoMiddle = null): self {
        $this->productInfoMiddle = $productInfoMiddle;

        return $this;
    }

    /**
     * @return ProductInfoMiddle|null
     */
    public function getProductInfoMiddle() {
        return $this->productInfoMiddle;
    }

    public function __toString(): string {
        return 'Gallery';
    }

    public function setImgFile(?UploadedFile $imgFile = null): self {
        $this->imgFile = $imgFile;
        $this->refreshUpdated();

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImgFile(): ?UploadedFile {
        return $this->imgFile;
    }

    public function uploadImgFile(): void {
        if (!$this->getImgFile() instanceof UploadedFile) {
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
        $this->getImgFile()->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->setImg(self::IMG_FOLDER . $fileName);
        $this->setImgFile(null);
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function lifecycleImgFileUpload(): void {
        $this->uploadImgFile();
    }

    public function refreshUpdated(): void {
        $this->setModified(new \DateTime());
    }

    /**
     * @ORM\PostRemove
     */
    public function removeImage(): void {
        $img = $this->getImg();
        if (($img !== null) && file_exists(FileHelper::DIR_PUBLIC . $img)) {
            @unlink(FileHelper::DIR_PUBLIC . $img);
        }
    }
}
