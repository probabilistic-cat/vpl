<?php

declare(strict_types=1);

namespace App\Entity;

use App\Helper\FileHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="property_item", indexes={@ORM\Index(name="ix__property_item__property_set_id", columns={"property_set_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PropertyItem implements \Stringable
{
    private const IMG_FOLDER = 'img/property_item/';

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
     * @var PropertySet
     *
     * @ORM\ManyToOne(targetEntity="PropertySet", inversedBy="propertyItems", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="property_set_id", referencedColumnName="id")
     * })
     */
    private $propertySet;

    private ?UploadedFile $imgFile = null;

    /**
     * Clone
     */
    public function __clone() {
        $this->id = null;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string|null $name
     */
    public function setName($name = null): self {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
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
        return $this->name ?? 'PropertyItem';
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

        $fileName = $this->createFileName();

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

    private function createFileName(): string {
        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $propItemIdId = empty($this->getId()) ? $microTimeStamp : $this->getId();

        $extension = ($this->getImgFile() instanceof UploadedFile)
            ? $this->getImgFile()->getClientOriginalExtension()
            : pathinfo($this->getImg(), PATHINFO_EXTENSION);

        $fileName = 'propitem_' . $propItemIdId . '.' . $extension;

        return $fileName;
    }

    /**
     * After clone and adding to property set
     */
    public function afterClone(): void {
        $cloneFileName = self::IMG_FOLDER . $this->createFileName();
        $originFileName = $this->getImg();

        try {
            copy($originFileName, $cloneFileName);
        } catch (\Exception) {
        }

        $this->setImg($cloneFileName);
    }

    public function actualizeFileName(): void {
        $actualFileName = self::IMG_FOLDER . $this->createFileName();

        if (strcmp($actualFileName, $this->getImg()) !== 0) {
            rename($this->getImg(), $actualFileName);
            $this->setImg($actualFileName);
        }
    }
}
