<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Table(name="category")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Category
{
    const IMG_FOLDER = 'img/category/';

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Subcategory", mappedBy="category")
     */
    private $subcategories;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CategoryProperty", mappedBy="category")
     */
    private $categoryProperties;

    /**
     * @var UploadedFile
     */
    private $imgFile;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subcategories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categoryProperties = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * @param string $color
     * @return Category
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param \DateTime $created
     * @return Category
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
     * @return Category
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
     * @param \AppBundle\Entity\Subcategory $subcategory
     * @return Category
     */
    public function addSubcategory(\AppBundle\Entity\Subcategory $subcategory)
    {
        $this->subcategories[] = $subcategory;

        return $this;
    }

    /**
     * @param \AppBundle\Entity\Subcategory $subcategory
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSubcategory(\AppBundle\Entity\Subcategory $subcategory)
    {
        return $this->subcategories->removeElement($subcategory);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubcategories()
    {
        return $this->subcategories;
    }

    /**
     * @param \AppBundle\Entity\CategoryProperty $categoryProperty
     * @return Category
     */
    public function addCategoryProperty(\AppBundle\Entity\CategoryProperty $categoryProperty)
    {
        $this->categoryProperties[] = $categoryProperty;

        return $this;
    }

    /**
     * @param \AppBundle\Entity\CategoryProperty $categoryProperty
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCategoryProperty(\AppBundle\Entity\CategoryProperty $categoryProperty)
    {
        return $this->categoryProperties->removeElement($categoryProperty);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoryProperties()
    {
        return $this->categoryProperties;
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

        $this->getImgFile()->move(self::IMG_FOLDER, $this->getImgFile()->getClientOriginalName());
        $this->setImg(self::IMG_FOLDER . $this->getImgFile()->getClientOriginalName());
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
}
