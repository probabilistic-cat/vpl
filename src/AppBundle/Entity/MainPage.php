<?php

namespace AppBundle\Entity;

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
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

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
     * @ORM\Column(name="map_src", type="text", length=65535, nullable=true)
     */
    private $mapSrc;

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
     * @var \AppBundle\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="second_line_1", referencedColumnName="id")
     * })
     */
    private $secondLine1;

    /**
     * @var \AppBundle\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
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

    /**
     * @var UploadedFile
     */
    private $secondLine2ImgFile;

    /**
     * @var UploadedFile
     */
    private $fourthLine2ImgFile;

    /**
     * @var UploadedFile
     */
    private $fourthLine3ImgFile;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string|null $phone
     * @return MainPage
     */
    public function setPhone($phone = null)
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
     * @return MainPage
     */
    public function setMail($mail = null)
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
     * @param string|null $mail
     * @return MainPage
     */
    public function setAddress($address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string|null $facebook
     * @return MainPage
     */
    public function setFacebook($facebook = null)
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
     * @return MainPage
     */
    public function setCopyright($copyright = null)
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
     * @param string|null $mapSrc
     * @return MainPage
     */
    public function setMapSrc($mapSrc = null)
    {
        $this->mapSrc = $mapSrc;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMapSrc()
    {
        return $this->mapSrc;
    }

    /**
     * @param string|null $secondLine2Img
     * @return MainPage
     */
    public function setSecondLine2Img($secondLine2Img = null)
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
     * @return MainPage
     */
    public function setSecondLine3Header($secondLine3Header = null)
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
     * @return MainPage
     */
    public function setSecondLine3Text($secondLine3Text = null)
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
     * @return MainPage
     */
    public function setFourthLine1Header($fourthLine1Header = null)
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
     * @return MainPage
     */
    public function setFourthLine1Text($fourthLine1Text = null)
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
     * @return MainPage
     */
    public function setFourthLine2Img($fourthLine2Img = null)
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
     * @return MainPage
     */
    public function setFourthLine2Header($fourthLine2Header = null)
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
     * @return MainPage
     */
    public function setFourthLine2Text($fourthLine2Text = null)
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
     * @return MainPage
     */
    public function setFourthLine3Img($fourthLine3Img = null)
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
     * @return MainPage
     */
    public function setFourthLine3Header($fourthLine3Header = null)
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
     * @return MainPage
     */
    public function setFourthLine3Text($fourthLine3Text = null)
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
     * @param \AppBundle\Entity\Product|null $secondLine1
     * @return MainPage
     */
    public function setSecondLine1(\AppBundle\Entity\Product $secondLine1 = null)
    {
        $this->secondLine1 = $secondLine1;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Product|null
     */
    public function getSecondLine1()
    {
        return $this->secondLine1;
    }

    /**
     * @param \AppBundle\Entity\Product|null $thirdLine1
     * @return MainPage
     */
    public function setThirdLine1(\AppBundle\Entity\Product $thirdLine1 = null)
    {
        $this->thirdLine1 = $thirdLine1;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Product|null
     */
    public function getThirdLine1()
    {
        return $this->thirdLine1;
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
     * @param UploadedFile $imgFile
     * @return MainPage
     */
    public function setSecondLine2ImgFile(UploadedFile $imgFile = null)
    {
        $this->secondLine2ImgFile = $imgFile;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSecondLine2ImgFile()
    {
        return $this->secondLine2ImgFile;
    }

    public function uploadSecondLine2ImgFile()
    {
        if (null === $this->getSecondLine2ImgFile()) {
            return;
        }

        $extension = $this->getSecondLine2ImgFile()->getClientOriginalExtension();
        $fileName = 'second_line_2_img.' . $extension;
        $this->getSecondLine2ImgFile()->move(self::IMG_FOLDER, $fileName);
        $this->setSecondLine2Img(self::IMG_FOLDER . $fileName);
        $this->setSecondLine2ImgFile(null);
    }

    /**
     * @param UploadedFile $imgFile
     * @return MainPage
     */
    public function setFourthLine2ImgFile(UploadedFile $imgFile = null)
    {
        $this->fourthLine2ImgFile = $imgFile;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFourthLine2ImgFile()
    {
        return $this->fourthLine2ImgFile;
    }

    public function uploadFourthLine2ImgFile()
    {
        if (null === $this->getFourthLine2ImgFile()) {
            return;
        }

        $extension = $this->getFourthLine2ImgFile()->getClientOriginalExtension();
        $fileName = 'fourth_line_2_img.' . $extension;
        $this->getFourthLine2ImgFile()->move(self::IMG_FOLDER, $fileName);
        $this->setFourthLine2Img(self::IMG_FOLDER . $fileName);
        $this->setFourthLine2ImgFile(null);
    }

    /**
     * @param UploadedFile $imgFile
     * @return MainPage
     */
    public function setFourthLine3ImgFile(UploadedFile $imgFile = null)
    {
        $this->fourthLine3ImgFile = $imgFile;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFourthLine3ImgFile()
    {
        return $this->fourthLine3ImgFile;
    }

    public function uploadFourthLine3ImgFile()
    {
        if (null === $this->getFourthLine3ImgFile()) {
            return;
        }

        $extension = $this->getFourthLine3ImgFile()->getClientOriginalExtension();
        $fileName = 'fourth_line_3_img.' . $extension;
        $this->getFourthLine3ImgFile()->move(self::IMG_FOLDER, $fileName);
        $this->setFourthLine3Img(self::IMG_FOLDER . $fileName);
        $this->setFourthLine3ImgFile(null);
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function lifecycleImgFileUpload()
    {
        $this->uploadSecondLine2ImgFile();
        $this->uploadFourthLine2ImgFile();
        $this->uploadFourthLine3ImgFile();
    }

    public function refreshUpdated()
    {
        $this->setModified(new \DateTime());
    }
}
