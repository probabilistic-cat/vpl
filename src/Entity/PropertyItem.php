<?php

namespace App\Entity;

use App\Helper\FileHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="property_item", indexes={@ORM\Index(name="ix__property_item__property_set_id", columns={"property_set_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PropertyItem
{
    const IMG_FOLDER = 'img/property_item/';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
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
     * @var \App\Entity\PropertySet
     *
     * @ORM\ManyToOne(targetEntity="PropertySet", inversedBy="propertyItems", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="property_set_id", referencedColumnName="id")
     * })
     */
    private $propertySet;

    /**
     * @var UploadedFile
     */
    private $imgFile;


    /**
     * Clone
     */
    public function __clone()
    {
        $this->id = null;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string|null $name
     * @return PropertyItem
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $img
     * @return PropertyItem
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
     * @return PropertyItem
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
     * @return PropertyItem
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
     * @return PropertyItem
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
     * @param \App\Entity\PropertySet|null $propertySet
     * @return PropertyItem
     */
    public function setPropertySet(\App\Entity\PropertySet $propertySet = null)
    {
        $this->propertySet = $propertySet;

        return $this;
    }

    /**
     * @return \App\Entity\PropertySet|null
     */
    public function getPropertySet()
    {
        return $this->propertySet;
    }

    public function __toString()
    {
        return $this->name ?? 'PropertyItem';;
    }

    /**
     * @param UploadedFile $imgFile
     * @return PropertyItem
     */
    public function setImgFile(UploadedFile $imgFile = null)
    {
        $this->imgFile = $imgFile;
        $this->refreshUpdated();

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

        $fileName = $this->createFileName();

        $this->getImgFile()->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
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
        $img = $this->getImg();
        if (($img !== null) && file_exists(FileHelper::DIR_PUBLIC . $img)) {
            @unlink(FileHelper::DIR_PUBLIC . $img);
        }
    }

    /**
     * @return string
     */
    private function createFileName()
    {
        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $propItemIdId = empty($this->getId()) ? $microTimeStamp : $this->getId();

        $extension = (null === $this->getImgFile())
            ? pathinfo($this->getImg(), PATHINFO_EXTENSION)
            : $this->getImgFile()->getClientOriginalExtension();

        $fileName = 'propitem_' . $propItemIdId . '.' . $extension;

        return $fileName;
    }

    /**
     * After clone and adding to property set
     */
    public function afterClone()
    {
        $cloneFileName = self::IMG_FOLDER . $this->createFileName();
        $originFileName = $this->getImg();

        try {
            copy($originFileName, $cloneFileName);
        } catch (\Exception $e) {}

        $this->setImg($cloneFileName);
    }

    public function actualizeFileName()
    {
        $actualFileName = self::IMG_FOLDER . $this->createFileName();

        if (strcmp($actualFileName, $this->getImg()) !== 0) {
            rename($this->getImg(), $actualFileName);
            $this->setImg($actualFileName);
        }
    }
}
