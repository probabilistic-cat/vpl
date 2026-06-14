<?php

declare(strict_types=1);

namespace App\Entity;

use App\Helper\FileHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="product_property", indexes={@ORM\Index(name="ix__product_property__product_id", columns={"product_id"}), @ORM\Index(name="ix__product_property__category_property_id", columns={"category_property_id"}), @ORM\Index(name="ix__prod_prop_set__property_set_id", columns={"property_set_id "})})
 * @ORM\Entity(repositoryClass="App\Repository\ProductPropertyRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ProductProperty implements \Stringable
{
    private const string IMG_FOLDER = 'img/product_property/';

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
     * @ORM\Column(name="name", type="text", length=255, nullable=true)
     */
    private $name;

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
     * @var CategoryProperty
     *
     * @ORM\ManyToOne(targetEntity="CategoryProperty", inversedBy="productProperties")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_property_id", referencedColumnName="id")
     * })
     */
    private $categoryProperty;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productProperties", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var PropertySet
     *
     * @ORM\ManyToOne(targetEntity="PropertySet", inversedBy="productProperties", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="property_set_id", referencedColumnName="id")
     * })
     */
    private $propertySet;

    private ?UploadedFile $imgFile = null;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name): self {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
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

    public function setCategoryProperty(?CategoryProperty $categoryProperty = null): self {
        $this->categoryProperty = $categoryProperty;

        return $this;
    }

    /**
     * @return CategoryProperty|null
     */
    public function getCategoryProperty() {
        return $this->categoryProperty;
    }

    public function setProduct(?Product $product = null): self {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct() {
        return $this->product;
    }

    public function setPropertySet(?PropertySet $propertySet = null): self {
        $this->propertySet = $propertySet;

        return $this;
    }

    /**
     * @return PropertySet|null
     */
    public function getPropertySet() {
        return $this->propertySet;
    }

    public function __toString(): string {
        return 'ProductProperty';
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

        $product = $this->getProduct();
        $subcategory = $product->getSubcategory();
        $category = $subcategory->getCategory();
        $categoryProperty = $this->getCategoryProperty();
        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $propId = empty($this->getId()) ? $microTimeStamp : $this->getId();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'cat_' . $category->getId() . '_subcat_' . $subcategory->getId() . '_prod_' . $product->getId()
            . '_cprop_' . $categoryProperty->getId() . '_pprop_' . $propId . '.' . $extension;
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
