<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="style_img", indexes={@ORM\Index(name="ix__style_img__style_id", columns={"style_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class StyleImg
{
    const IMG_FOLDER = 'img/style/';

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
     * @var \AppBundle\Entity\Style
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Style", inversedBy="categoryProperties", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="style_id", referencedColumnName="id")
     * })
     */
    private $style;

    /**
     * @var UploadedFile
     */
    private $imgFile;

    /**
     * @var UploadedFile
     */
    private $imgColorFile;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string|null $img
     * @return StyleImg
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
     * @param string|null $imgColor
     * @return StyleImg
     */
    public function setImgColor($imgColor = null)
    {
        $this->imgColor = $imgColor;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImgColor()
    {
        return $this->imgColor;
    }

    /**
     * @param int $seq
     * @return StyleImg
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
     * @return StyleImg
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
     * @return StyleImg
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
     * @param \AppBundle\Entity\Style|null $style
     * @return StyleImg
     */
    public function setStyle(\AppBundle\Entity\Style $style = null)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Style|null
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param UploadedFile $imgFile
     * @return StyleImg
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

        $style = $this->getStyle();
        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $styleImgId = empty($this->getId()) ? $microTimeStamp : $this->getId();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'style_' . $style->getId() . '_img_' . $styleImgId . '.' . $extension;
        $this->getImgFile()->move(self::IMG_FOLDER, $fileName);
        $this->setImg(self::IMG_FOLDER . $fileName);
        $this->setImgFile(null);
    }

    /**
     * @param UploadedFile $imgFile
     * @return StyleImg
     */
    public function setImgColorFile(UploadedFile $imgColorFile = null)
    {
        $this->imgColorFile = $imgColorFile;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImgColorFile()
    {
        return $this->imgColorFile;
    }

    public function uploadImgColorFile()
    {
        if (null === $this->getImgColorFile()) {
            return;
        }

        $style = $this->getStyle();
        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $styleImgId = empty($this->getId()) ? $microTimeStamp : $this->getId();

        $extension = $this->getImgColorFile()->getClientOriginalExtension();
        $fileName = 'style_' . $style->getId() . '_img_color_' . $styleImgId . '.' . $extension;
        $this->getImgColorFile()->move(self::IMG_FOLDER, $fileName);
        $this->setImgColor(self::IMG_FOLDER . $fileName);
        $this->setImgColorFile(null);
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function lifecycleImgFileUpload()
    {
        $this->uploadImgFile();
        $this->uploadImgColorFile();
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
        if (file_exists($this->getImgColor())) {
            @unlink($this->getImgColor());
        }
    }
}
