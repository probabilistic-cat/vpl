<?php

declare(strict_types=1);

namespace App\Entity;

use App\Helper\FileHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Category
{
    private const IMG_FOLDER = 'img/category/';

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
     * @ORM\Column(name="img", type="text", length=65535, nullable=true)
     */
    private $img;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255, nullable=false, options={"default"="#c9eeff"})
     */
    private $color = '#c9eeff';

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
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Subcategory", mappedBy="category")
     */
    private $subcategories;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="CategoryProperty", mappedBy="category", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"seq" = "ASC"})
     */
    private $categoryProperties;

    private ?UploadedFile $imgFile = null;

    /**
     * Constructor
     */
    public function __construct() {
        $this->subcategories = new ArrayCollection();
        $this->categoryProperties = new ArrayCollection();
    }

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
     * @param string|null $description
     */
    public function setDescription($description = null): self {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string|null $img
     */
    public function setImg($img = null): self {
        $this->img = $img;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImg() {
        return $this->img;
    }

    /**
     * @param string $color
     */
    public function setColor($color): self {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor() {
        return $this->color;
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

    public function addSubcategory(Subcategory $subcategory): self {
        $this->subcategories[] = $subcategory;

        return $this;
    }

    /**
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeSubcategory(Subcategory $subcategory) {
        return $this->subcategories->removeElement($subcategory);
    }

    /**
     * @return Collection
     */
    public function getSubcategories() {
        return $this->subcategories;
    }

    public function addCategoryProperty(CategoryProperty $categoryProperty): self {
        $categoryProperty->setCategory($this);
        $this->categoryProperties[] = $categoryProperty;

        return $this;
    }

    /**
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeCategoryProperty(CategoryProperty $categoryProperty) {
        return $this->categoryProperties->removeElement($categoryProperty);
    }

    /**
     * @return Collection
     */
    public function getCategoryProperties() {
        return $this->categoryProperties;
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

        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $categoryId = empty($this->getId()) ? $microTimeStamp : $this->getId();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'cat_' . $categoryId . '.' . $extension;
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
