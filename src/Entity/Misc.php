<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="misc")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Misc
{
    const ID = 1;
    const IMG_FOLDER = 'img/misc/';

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
     * @ORM\Column(name="design_name", type="string", length=255, nullable=false)
     */
    private $designName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="design_description", type="text", length=65535, nullable=true)
     */
    private $designDescription;

    /**
     * @var string|null
     *
     * @ORM\Column(name="design_img", type="text", length=65535, nullable=true)
     */
    private $designImg;

    /**
     * @var string
     *
     * @ORM\Column(name="categories_name", type="string", length=255, nullable=false)
     */
    private $categoriesName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="categories_description", type="text", length=65535, nullable=true)
     */
    private $categoriesDescription;

    /**
     * @var string|null
     *
     * @ORM\Column(name="contact_address", type="string", length=255, nullable=true)
     */
    private $contactAddress;

    /**
     * @var string|null
     *
     * @ORM\Column(name="contact_map_src", type="text", length=65535, nullable=true)
     */
    private $contactMapSrc;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @var UploadedFile
     */
    private $designImgFile;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $designName
     * @return Misc
     */
    public function setDesignName($designName)
    {
        $this->designName = $designName;

        return $this;
    }

    /**
     * @return string
     */
    public function getDesignName()
    {
        return $this->designName;
    }

    /**
     * @param string|null $designDescription
     * @return Misc
     */
    public function setDesignDescription($designDescription = null)
    {
        $this->designDescription = $designDescription;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDesignDescription()
    {
        return $this->designDescription;
    }

    /**
     * @param string|null $designImg
     * @return Misc
     */
    public function setDesignImg($designImg = null)
    {
        $this->designImg = $designImg;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDesignImg()
    {
        return $this->designImg;
    }

    /**
     * @param string $categoriesName
     * @return Misc
     */
    public function setCategoriesName($categoriesName)
    {
        $this->categoriesName = $categoriesName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategoriesName()
    {
        return $this->categoriesName;
    }

    /**
     * @param string|null $categoriesDescription
     * @return Misc
     */
    public function setCategoriesDescription($categoriesDescription = null)
    {
        $this->categoriesDescription = $categoriesDescription;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategoriesDescription()
    {
        return $this->categoriesDescription;
    }

    /**
     * @param string|null $contactAddress
     * @return Misc
     */
    public function setContactAddress($contactAddress = null)
    {
        $this->contactAddress = $contactAddress;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContactAddress()
    {
        return $this->contactAddress;
    }

    /**
     * @param string|null $contactMapSrc
     * @return Misc
     */
    public function setContactMapSrc($contactMapSrc = null)
    {
        $this->contactMapSrc = $contactMapSrc;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContactMapSrc()
    {
        return $this->contactMapSrc;
    }

    /**
     * @param \DateTime|null $modified
     * @return Misc
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
     * @param UploadedFile $designImgFile
     * @return Misc
     */
    public function setDesignImgFile(UploadedFile $designImgFile = null)
    {
        $this->designImgFile = $designImgFile;
        $this->refreshUpdated();

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDesignImgFile()
    {
        return $this->designImgFile;
    }

    public function uploadDesignImgFile()
    {
        if (null === $this->getDesignImgFile()) {
            return;
        }

        $extension = $this->getDesignImgFile()->getClientOriginalExtension();
        $fileName = 'design_img.' . $extension;
        $this->getDesignImgFile()->move(self::IMG_FOLDER, $fileName);
        $this->setDesignImg(self::IMG_FOLDER . $fileName);
        $this->setDesignImgFile(null);
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function lifecycleImgFileUpload()
    {
        $this->uploadDesignImgFile();
    }

    public function refreshUpdated()
    {
        $this->setModified(new \DateTime());
    }
}
