<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Helper\FileHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="subcategory", indexes={@ORM\Index(name="ix__subcategory__category_id", columns={"category_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\SubcategoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Subcategory
{
    const IMG_FOLDER = 'img/subcategory/';

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
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="subcategories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="subcategory")
     * @ORM\OrderBy({"seq" = "ASC"})
     */
    private $products;

    private ?UploadedFile $imgFile = null;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * @param string|null $description
     */
    public function setDescription($description = null): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string|null $img
     */
    public function setImg($img = null): self
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

    /**
     * @param Category|null $category
     */
    public function setCategory(Category $category = null): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function addProduct(Product $product): self
    {
        $this->products[] = $product;
        return $this;
    }

    /**
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduct(Product $product)
    {
        return $this->products->removeElement($product);
    }

    /**
     * @return Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    public function setImgFile(UploadedFile $imgFile = null): self
    {
        $this->imgFile = $imgFile;
        $this->refreshUpdated();

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImgFile(): ?UploadedFile
    {
        return $this->imgFile;
    }

    public function uploadImgFile(): void
    {
        if (!($this->getImgFile() instanceof UploadedFile)) {
            return;
        }

        $category = $this->getCategory();
        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $subcategoryId = empty($this->getId()) ? $microTimeStamp : $this->getId();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'cat_' . $category->getId() . '_subcat_' . $subcategoryId . '.' . $extension;
        $this->getImgFile()->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->setImg(self::IMG_FOLDER . $fileName);
        $this->setImgFile(null);
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function lifecycleImgFileUpload(): void
    {
        $this->uploadImgFile();
    }

    public function refreshUpdated(): void
    {
        $this->setModified(new \DateTime());
    }

    /**
     * @ORM\PostRemove
     */
    public function removeImage(): void
    {
        $img = $this->getImg();
        if (($img !== null) && file_exists(FileHelper::DIR_PUBLIC . $img)) {
            @unlink(FileHelper::DIR_PUBLIC . $img);
        }
    }
}
