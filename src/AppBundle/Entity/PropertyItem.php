<?php

namespace AppBundle\Entity;

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
     * @var \AppBundle\Entity\PropertySet
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PropertySet", inversedBy="propertyItems", cascade={"persist"})
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
     * @param \AppBundle\Entity\PropertySet|null $propertySet
     * @return PropertyItem
     */
    public function setPropertySet(\AppBundle\Entity\PropertySet $propertySet = null)
    {
        $this->propertySet = $propertySet;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\PropertySet|null
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

        $propertSet = $this->getPropertySet();
        $property = $propertSet->getProperty();
        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $propItemIdId = empty($this->getId()) ? $microTimeStamp : $this->getId();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'prop_' . $property->getId() . '_propset_' . $propertSet->getId() . '_propitem_' . $propItemIdId
            . '.' . $extension;
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
            @unlink($this->getImg());
        }
    }
}
