<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="main_page", indexes={@ORM\Index(name="ix__main_page__third_line_1", columns={"third_line_1"}), @ORM\Index(name="ix__main_page__second_line_1", columns={"second_line_1"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class MainPage
{
    const ID = 1;
    const IMG_FOLDER = 'img/main_page/';

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
     * @ORM\Column(name="phone", type="string", length=32, nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @var string|null
     *
     * @ORM\Column(name="copyright", type="string", length=255, nullable=true)
     */
    private $copyright;

    /**
     * @var string|null
     *
     * @ORM\Column(name="second_line_2_img", type="text", length=65535, nullable=true)
     */
    private $secondLine2Img;

    /**
     * @var string|null
     *
     * @ORM\Column(name="second_line_3_header", type="string", length=255, nullable=true)
     */
    private $secondLine3Header;

    /**
     * @var string|null
     *
     * @ORM\Column(name="second_line_3_text", type="text", length=65535, nullable=true)
     */
    private $secondLine3Text;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fourth_line_1_header", type="string", length=255, nullable=true)
     */
    private $fourthLine1Header;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fourth_line_1_text", type="text", length=65535, nullable=true)
     */
    private $fourthLine1Text;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fourth_line_2_img", type="text", length=65535, nullable=true)
     */
    private $fourthLine2Img;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fourth_line_2_header", type="string", length=255, nullable=true)
     */
    private $fourthLine2Header;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fourth_line_2_text", type="text", length=65535, nullable=true)
     */
    private $fourthLine2Text;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fourth_line_3_img", type="text", length=65535, nullable=true)
     */
    private $fourthLine3Img;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fourth_line_3_header", type="string", length=255, nullable=true)
     */
    private $fourthLine3Header;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fourth_line_3_text", type="text", length=65535, nullable=true)
     */
    private $fourthLine3Text;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="second_line_1", referencedColumnName="id")
     * })
     */
    private $secondLine1;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="third_line_1", referencedColumnName="id")
     * })
     */
    private $thirdLine1;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    private $modified;

    private ?UploadedFile $secondLine2ImgFile = null;

    private ?UploadedFile $fourthLine2ImgFile = null;

    private ?UploadedFile $fourthLine3ImgFile = null;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone($phone = null): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string|null $mail
     */
    public function setMail($mail = null): self
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param string|null $facebook
     */
    public function setFacebook($facebook = null): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param string|null $copyright
     */
    public function setCopyright($copyright = null): self
    {
        $this->phone = $copyright;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * @param string|null $secondLine2Img
     */
    public function setSecondLine2Img($secondLine2Img = null): self
    {
        $this->secondLine2Img = $secondLine2Img;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSecondLine2Img()
    {
        return $this->secondLine2Img;
    }

    /**
     * @param string|null $secondLine3Header
     */
    public function setSecondLine3Header($secondLine3Header = null): self
    {
        $this->secondLine3Header = $secondLine3Header;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSecondLine3Header()
    {
        return $this->secondLine3Header;
    }

    /**
     * @param string|null $secondLine3Text
     */
    public function setSecondLine3Text($secondLine3Text = null): self
    {
        $this->secondLine3Text = $secondLine3Text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSecondLine3Text()
    {
        return $this->secondLine3Text;
    }

    /**
     * @param string|null $fourthLine1Header
     */
    public function setFourthLine1Header($fourthLine1Header = null): self
    {
        $this->fourthLine1Header = $fourthLine1Header;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFourthLine1Header()
    {
        return $this->fourthLine1Header;
    }

    /**
     * @param string|null $fourthLine1Text
     */
    public function setFourthLine1Text($fourthLine1Text = null): self
    {
        $this->fourthLine1Text = $fourthLine1Text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFourthLine1Text()
    {
        return $this->fourthLine1Text;
    }

    /**
     * @param string|null $fourthLine2Img
     */
    public function setFourthLine2Img($fourthLine2Img = null): self
    {
        $this->fourthLine2Img = $fourthLine2Img;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFourthLine2Img()
    {
        return $this->fourthLine2Img;
    }

    /**
     * @param string|null $fourthLine2Header
     */
    public function setFourthLine2Header($fourthLine2Header = null): self
    {
        $this->fourthLine2Header = $fourthLine2Header;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFourthLine2Header()
    {
        return $this->fourthLine2Header;
    }

    /**
     * @param string|null $fourthLine2Text
     */
    public function setFourthLine2Text($fourthLine2Text = null): self
    {
        $this->fourthLine2Text = $fourthLine2Text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFourthLine2Text()
    {
        return $this->fourthLine2Text;
    }

    /**
     * @param string|null $fourthLine3Img
     */
    public function setFourthLine3Img($fourthLine3Img = null): self
    {
        $this->fourthLine3Img = $fourthLine3Img;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFourthLine3Img()
    {
        return $this->fourthLine3Img;
    }

    /**
     * @param string|null $fourthLine3Header
     */
    public function setFourthLine3Header($fourthLine3Header = null): self
    {
        $this->fourthLine3Header = $fourthLine3Header;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFourthLine3Header()
    {
        return $this->fourthLine3Header;
    }

    /**
     * @param string|null $fourthLine3Text
     */
    public function setFourthLine3Text($fourthLine3Text = null): self
    {
        $this->fourthLine3Text = $fourthLine3Text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFourthLine3Text()
    {
        return $this->fourthLine3Text;
    }

    /**
     * @param Product|null $secondLine1
     */
    public function setSecondLine1(Product $secondLine1 = null): self
    {
        $this->secondLine1 = $secondLine1;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getSecondLine1()
    {
        return $this->secondLine1;
    }

    /**
     * @param Product|null $thirdLine1
     */
    public function setThirdLine1(Product $thirdLine1 = null): self
    {
        $this->thirdLine1 = $thirdLine1;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getThirdLine1()
    {
        return $this->thirdLine1;
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

    public function setSecondLine2ImgFile(UploadedFile $imgFile = null): self
    {
        $this->secondLine2ImgFile = $imgFile;
        $this->refreshUpdated();

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSecondLine2ImgFile(): ?UploadedFile
    {
        return $this->secondLine2ImgFile;
    }

    public function uploadSecondLine2ImgFile(): void
    {
        if (!($this->getSecondLine2ImgFile() instanceof UploadedFile)) {
            return;
        }

        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $mainPageId = empty($this->getId()) ? $microTimeStamp : $this->getId();
        $extension = $this->getSecondLine2ImgFile()->getClientOriginalExtension();
        $fileName = 'second_line_2_img_' . $mainPageId . '.' . $extension;
        $this->getSecondLine2ImgFile()->move(self::IMG_FOLDER, $fileName);
        $this->setSecondLine2Img(self::IMG_FOLDER . $fileName);
        $this->setSecondLine2ImgFile(null);
    }

    public function setFourthLine2ImgFile(UploadedFile $imgFile = null): self
    {
        $this->fourthLine2ImgFile = $imgFile;
        $this->refreshUpdated();

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFourthLine2ImgFile(): ?UploadedFile
    {
        return $this->fourthLine2ImgFile;
    }

    public function uploadFourthLine2ImgFile(): void
    {
        if (!($this->getFourthLine2ImgFile() instanceof UploadedFile)) {
            return;
        }

        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $mainPageId = empty($this->getId()) ? $microTimeStamp : $this->getId();
        $extension = $this->getFourthLine2ImgFile()->getClientOriginalExtension();
        $fileName = 'fourth_line_2_img_' . $mainPageId . '.' . $extension;
        $this->getFourthLine2ImgFile()->move(self::IMG_FOLDER, $fileName);
        $this->setFourthLine2Img(self::IMG_FOLDER . $fileName);
        $this->setFourthLine2ImgFile(null);
    }

    public function setFourthLine3ImgFile(UploadedFile $imgFile = null): self
    {
        $this->fourthLine3ImgFile = $imgFile;
        $this->refreshUpdated();

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFourthLine3ImgFile(): ?UploadedFile
    {
        return $this->fourthLine3ImgFile;
    }

    public function uploadFourthLine3ImgFile(): void
    {
        if (!($this->getFourthLine3ImgFile() instanceof UploadedFile)) {
            return;
        }

        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $mainPageId = empty($this->getId()) ? $microTimeStamp : $this->getId();
        $extension = $this->getFourthLine3ImgFile()->getClientOriginalExtension();
        $fileName = 'fourth_line_3_img_' . $mainPageId . '.' . $extension;
        $this->getFourthLine3ImgFile()->move(self::IMG_FOLDER, $fileName);
        $this->setFourthLine3Img(self::IMG_FOLDER . $fileName);
        $this->setFourthLine3ImgFile(null);
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function lifecycleImgFileUpload(): void
    {
        $this->uploadSecondLine2ImgFile();
        $this->uploadFourthLine2ImgFile();
        $this->uploadFourthLine3ImgFile();
    }

    public function refreshUpdated(): void
    {
        $this->setModified(new \DateTime());
    }
}
