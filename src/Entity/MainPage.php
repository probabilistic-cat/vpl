<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
#[ORM\Table(name: 'main_page')]
#[ORM\Index(name: 'ix__main_page__third_line_1', columns: ['third_line_1'])]
#[ORM\Index(name: 'ix__main_page__second_line_1', columns: ['second_line_1'])]
#[ORM\HasLifecycleCallbacks]
class MainPage
{
    public const int ID = 1;
    private const string IMG_FOLDER = 'img/main_page/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(nullable: true)]
    private ?string $mail = null;

    #[ORM\Column(nullable: true)]
    private ?string $facebook = null;

    #[ORM\Column(nullable: true)]
    private ?string $copyright = null;

    #[ORM\Column(name: 'second_line_2_img', type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $secondLine2Img = null;

    #[ORM\Column(name: 'second_line_3_header', nullable: true)]
    private ?string $secondLine3Header = null;

    #[ORM\Column(name: 'second_line_3_text', type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $secondLine3Text = null;

    #[ORM\Column(name: 'fourth_line_1_header', nullable: true)]
    private ?string $fourthLine1Header = null;

    #[ORM\Column(name: 'fourth_line_1_text', type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $fourthLine1Text = null;

    #[ORM\Column(name: 'fourth_line_2_img', type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $fourthLine2Img = null;

    #[ORM\Column(name: 'fourth_line_2_header', nullable: true)]
    private ?string $fourthLine2Header = null;

    #[ORM\Column(name: 'fourth_line_2_text', type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $fourthLine2Text = null;

    #[ORM\Column(name: 'fourth_line_3_img', type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $fourthLine3Img = null;

    #[ORM\Column(name: 'fourth_line_3_header', nullable: true)]
    private ?string $fourthLine3Header = null;

    #[ORM\Column(name: 'fourth_line_3_text', type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $fourthLine3Text = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'second_line_1', referencedColumnName: 'id')]
    private Product $secondLine1;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'third_line_1', referencedColumnName: 'id')]
    private Product $thirdLine1;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    private ?UploadedFile $secondLine2ImgFile = null;

    private ?UploadedFile $fourthLine2ImgFile = null;

    private ?UploadedFile $fourthLine3ImgFile = null;

    public function getId(): int {
        return $this->id;
    }

    public function setPhone(?string $phone = null): self {
        $this->phone = $phone;

        return $this;
    }

    public function getPhone(): ?string {
        return $this->phone;
    }

    public function setMail(?string $mail = null): self {
        $this->mail = $mail;

        return $this;
    }

    public function getMail(): ?string {
        return $this->mail;
    }

    public function setFacebook(?string $facebook = null): self {
        $this->facebook = $facebook;

        return $this;
    }

    public function getFacebook(): ?string {
        return $this->facebook;
    }

    public function setCopyright(?string $copyright = null): self {
        $this->phone = $copyright;

        return $this;
    }

    public function getCopyright(): ?string {
        return $this->copyright;
    }

    public function setSecondLine2Img(?string $secondLine2Img = null): self {
        $this->secondLine2Img = $secondLine2Img;

        return $this;
    }

    public function getSecondLine2Img(): ?string {
        return $this->secondLine2Img;
    }

    public function setSecondLine3Header(?string $secondLine3Header = null): self {
        $this->secondLine3Header = $secondLine3Header;

        return $this;
    }

    public function getSecondLine3Header(): ?string {
        return $this->secondLine3Header;
    }

    public function setSecondLine3Text(?string $secondLine3Text = null): self {
        $this->secondLine3Text = $secondLine3Text;

        return $this;
    }

    public function getSecondLine3Text(): ?string {
        return $this->secondLine3Text;
    }

    public function setFourthLine1Header(?string $fourthLine1Header = null): self {
        $this->fourthLine1Header = $fourthLine1Header;

        return $this;
    }

    public function getFourthLine1Header(): ?string {
        return $this->fourthLine1Header;
    }

    public function setFourthLine1Text(?string $fourthLine1Text = null): self {
        $this->fourthLine1Text = $fourthLine1Text;

        return $this;
    }

    public function getFourthLine1Text(): ?string {
        return $this->fourthLine1Text;
    }

    public function setFourthLine2Img(?string $fourthLine2Img = null): self {
        $this->fourthLine2Img = $fourthLine2Img;

        return $this;
    }

    public function getFourthLine2Img(): ?string {
        return $this->fourthLine2Img;
    }

    public function setFourthLine2Header(?string $fourthLine2Header = null): self {
        $this->fourthLine2Header = $fourthLine2Header;

        return $this;
    }

    public function getFourthLine2Header(): ?string {
        return $this->fourthLine2Header;
    }

    public function setFourthLine2Text(?string $fourthLine2Text = null): self {
        $this->fourthLine2Text = $fourthLine2Text;

        return $this;
    }

    public function getFourthLine2Text(): ?string {
        return $this->fourthLine2Text;
    }

    public function setFourthLine3Img(?string $fourthLine3Img = null): self {
        $this->fourthLine3Img = $fourthLine3Img;

        return $this;
    }

    public function getFourthLine3Img(): ?string {
        return $this->fourthLine3Img;
    }

    public function setFourthLine3Header(?string $fourthLine3Header = null): self {
        $this->fourthLine3Header = $fourthLine3Header;

        return $this;
    }

    public function getFourthLine3Header(): ?string {
        return $this->fourthLine3Header;
    }

    public function setFourthLine3Text(?string $fourthLine3Text = null): self {
        $this->fourthLine3Text = $fourthLine3Text;

        return $this;
    }

    public function getFourthLine3Text(): ?string {
        return $this->fourthLine3Text;
    }

    public function setSecondLine1(?Product $secondLine1 = null): self {
        $this->secondLine1 = $secondLine1;

        return $this;
    }

    public function getSecondLine1(): Product {
        return $this->secondLine1;
    }

    public function setThirdLine1(?Product $thirdLine1 = null): self {
        $this->thirdLine1 = $thirdLine1;

        return $this;
    }

    public function getThirdLine1(): Product {
        return $this->thirdLine1;
    }

    public function setModified(?\DateTime $modified = null): self {
        $this->modified = $modified;

        return $this;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function setSecondLine2ImgFile(?UploadedFile $imgFile = null): self {
        $this->secondLine2ImgFile = $imgFile;
        $this->refreshUpdated();

        return $this;
    }

    public function getSecondLine2ImgFile(): ?UploadedFile {
        return $this->secondLine2ImgFile;
    }

    public function uploadSecondLine2ImgFile(): void {
        if (!$this->getSecondLine2ImgFile() instanceof UploadedFile) {
            return;
        }

        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $mainPageId = $this->getId() !== null ? $this->getId() : $microTimeStamp;
        $extension = $this->getSecondLine2ImgFile()->getClientOriginalExtension();
        $fileName = 'second_line_2_img_' . $mainPageId . '.' . $extension;
        $this->getSecondLine2ImgFile()->move(self::IMG_FOLDER, $fileName);
        $this->setSecondLine2Img(self::IMG_FOLDER . $fileName);
        $this->setSecondLine2ImgFile(null);
    }

    public function setFourthLine2ImgFile(?UploadedFile $imgFile = null): self {
        $this->fourthLine2ImgFile = $imgFile;
        $this->refreshUpdated();

        return $this;
    }

    public function getFourthLine2ImgFile(): ?UploadedFile {
        return $this->fourthLine2ImgFile;
    }

    public function uploadFourthLine2ImgFile(): void {
        if (!$this->getFourthLine2ImgFile() instanceof UploadedFile) {
            return;
        }

        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $mainPageId = $this->getId() !== null ? $this->getId() : $microTimeStamp;
        $extension = $this->getFourthLine2ImgFile()->getClientOriginalExtension();
        $fileName = 'fourth_line_2_img_' . $mainPageId . '.' . $extension;
        $this->getFourthLine2ImgFile()->move(self::IMG_FOLDER, $fileName);
        $this->setFourthLine2Img(self::IMG_FOLDER . $fileName);
        $this->setFourthLine2ImgFile(null);
    }

    public function setFourthLine3ImgFile(?UploadedFile $imgFile = null): self {
        $this->fourthLine3ImgFile = $imgFile;
        $this->refreshUpdated();

        return $this;
    }

    public function getFourthLine3ImgFile(): ?UploadedFile {
        return $this->fourthLine3ImgFile;
    }

    public function uploadFourthLine3ImgFile(): void {
        if (!$this->getFourthLine3ImgFile() instanceof UploadedFile) {
            return;
        }

        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $mainPageId = $this->getId() !== null ? $this->getId() : $microTimeStamp;
        $extension = $this->getFourthLine3ImgFile()->getClientOriginalExtension();
        $fileName = 'fourth_line_3_img_' . $mainPageId . '.' . $extension;
        $this->getFourthLine3ImgFile()->move(self::IMG_FOLDER, $fileName);
        $this->setFourthLine3Img(self::IMG_FOLDER . $fileName);
        $this->setFourthLine3ImgFile(null);
    }

    #[ORM\PreUpdate]
    public function lifecycleImgFileUpload(): void {
        $this->uploadSecondLine2ImgFile();
        $this->uploadFourthLine2ImgFile();
        $this->uploadFourthLine3ImgFile();
    }

    public function refreshUpdated(): void {
        $this->setModified(new \DateTime());
    }
}
