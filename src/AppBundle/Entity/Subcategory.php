<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="subcategory", indexes={@ORM\Index(name="ix__subcategory__category_id", columns={"category_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubcategoryRepository")
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
     * @var \AppBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="subcategories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="subcategory")
     * @ORM\OrderBy({"seq" = "ASC"})
     */
    private $products;

    /**
     * @var UploadedFile
     */
    private $imgFile;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Subcategory
     */
    public function setName($name)
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
     * @return Subcategory
     */
    public function setDescription($description = null)
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
     * @return Subcategory
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
     * @param \DateTime $created
     * @return Subcategory
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
     * @return Subcategory
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
     * @param \AppBundle\Entity\Category|null $category
     * @return Subcategory
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param \AppBundle\Entity\Product $product
     * @return Subcategory
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $this->products[] = $product;
        return $this;
    }

    /**
     * @param \AppBundle\Entity\Product $product
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        return $this->products->removeElement($product);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param UploadedFile $imgFile
     * @return Subategory
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

        $category = $this->getCategory();
        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $subcategoryId = empty($this->getId()) ? $microTimeStamp : $this->getId();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'cat_' . $category->getId() . '_subcat_' . $subcategoryId . '.' . $extension;
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
