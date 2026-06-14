<?php

declare(strict_types=1);

namespace App\Entity;

use App\Helper\FileHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="style_img", indexes={@ORM\Index(name="ix__style_img__style_id", columns={"style_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class StyleImg
{
    private const string IMG_FOLDER = 'img/style/';

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
     * @ORM\Column(name="img", type="text", length=65535, nullable=true)
     */
    private $img;

    /**
     * @var string|null
     *
     * @ORM\Column(name="img_color", type="text", length=65535, nullable=true)
     */
    private $imgColor;

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
     * @var Style
     *
     * @ORM\ManyToOne(targetEntity="Style", inversedBy="styleImgs", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="style_id", referencedColumnName="id")
     * })
     */
    private $style;

    private ?UploadedFile $imgFile = null;

    private ?UploadedFile $imgColorFile = null;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
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
     * @param string|null $imgColor
     */
    public function setImgColor($imgColor = null): self {
        $this->imgColor = $imgColor;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImgColor() {
        return $this->imgColor;
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

    public function setStyle(?Style $style = null): self {
        $this->style = $style;

        return $this;
    }

    /**
     * @return Style|null
     */
    public function getStyle() {
        return $this->style;
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

        $style = $this->getStyle();
        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $styleImgId = empty($this->getId()) ? $microTimeStamp : $this->getId();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'style_' . $style->getId() . '_img_' . $styleImgId . '.' . $extension;
        $this->getImgFile()->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->setImg(self::IMG_FOLDER . $fileName);
        $this->setImgFile(null);
    }

    public function setImgColorFile(?UploadedFile $imgColorFile = null): self {
        $this->imgColorFile = $imgColorFile;
        $this->refreshUpdated();

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImgColorFile(): ?UploadedFile {
        return $this->imgColorFile;
    }

    public function uploadImgColorFile(): void {
        if (!$this->getImgColorFile() instanceof UploadedFile) {
            return;
        }

        $style = $this->getStyle();
        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $styleImgId = empty($this->getId()) ? $microTimeStamp : $this->getId();

        $extension = $this->getImgColorFile()->getClientOriginalExtension();
        $fileName = 'style_' . $style->getId() . '_img_color_' . $styleImgId . '.' . $extension;
        $this->getImgColorFile()->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->setImgColor(self::IMG_FOLDER . $fileName);
        $this->setImgColorFile(null);
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function lifecycleImgFileUpload(): void {
        $this->uploadImgFile();
        $this->uploadImgColorFile();
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
        $imgColor = $this->getImgColor();
        if (($imgColor !== null) && file_exists(FileHelper::DIR_PUBLIC . $imgColor)) {
            @unlink(FileHelper::DIR_PUBLIC . $imgColor);
        }
    }
}
