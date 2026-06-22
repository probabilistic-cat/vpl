<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MainPageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: MainPageRepository::class)]
#[ORM\Table(name: 'main_page')]
#[ORM\Index(name: 'ix__main_page__third_line_1', columns: ['third_line_1'])]
#[ORM\Index(name: 'ix__main_page__second_line_1', columns: ['second_line_1'])]
#[ORM\HasLifecycleCallbacks]
class MainPage
{
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

    public function setPhone(?string $phone): void {
        $this->phone = $phone;
    }

    public function getPhone(): ?string {
        return $this->phone;
    }

    public function setMail(?string $mail): void {
        $this->mail = $mail;
    }

    public function getMail(): ?string {
        return $this->mail;
    }

    public function setFacebook(?string $facebook): void {
        $this->facebook = $facebook;
    }

    public function getFacebook(): ?string {
        return $this->facebook;
    }

    public function setCopyright(?string $copyright): void {
        $this->phone = $copyright;
    }

    public function getCopyright(): ?string {
        return $this->copyright;
    }

    public function setSecondLine2Img(?string $secondLine2Img): void {
        $this->secondLine2Img = $secondLine2Img;
    }

    public function getSecondLine2Img(): ?string {
        return $this->secondLine2Img;
    }

    public function setSecondLine3Header(?string $secondLine3Header): void {
        $this->secondLine3Header = $secondLine3Header;
    }

    public function getSecondLine3Header(): ?string {
        return $this->secondLine3Header;
    }

    public function setSecondLine3Text(?string $secondLine3Text): void {
        $this->secondLine3Text = $secondLine3Text;
    }

    public function getSecondLine3Text(): ?string {
        return $this->secondLine3Text;
    }

    public function setFourthLine1Header(?string $fourthLine1Header): void {
        $this->fourthLine1Header = $fourthLine1Header;
    }

    public function getFourthLine1Header(): ?string {
        return $this->fourthLine1Header;
    }

    public function setFourthLine1Text(?string $fourthLine1Text): void {
        $this->fourthLine1Text = $fourthLine1Text;
    }

    public function getFourthLine1Text(): ?string {
        return $this->fourthLine1Text;
    }

    public function setFourthLine2Img(?string $fourthLine2Img): void {
        $this->fourthLine2Img = $fourthLine2Img;
    }

    public function getFourthLine2Img(): ?string {
        return $this->fourthLine2Img;
    }

    public function setFourthLine2Header(?string $fourthLine2Header): void {
        $this->fourthLine2Header = $fourthLine2Header;
    }

    public function getFourthLine2Header(): ?string {
        return $this->fourthLine2Header;
    }

    public function setFourthLine2Text(?string $fourthLine2Text): void {
        $this->fourthLine2Text = $fourthLine2Text;
    }

    public function getFourthLine2Text(): ?string {
        return $this->fourthLine2Text;
    }

    public function setFourthLine3Img(?string $fourthLine3Img): void {
        $this->fourthLine3Img = $fourthLine3Img;
    }

    public function getFourthLine3Img(): ?string {
        return $this->fourthLine3Img;
    }

    public function setFourthLine3Header(?string $fourthLine3Header): void {
        $this->fourthLine3Header = $fourthLine3Header;
    }

    public function getFourthLine3Header(): ?string {
        return $this->fourthLine3Header;
    }

    public function setFourthLine3Text(?string $fourthLine3Text): void {
        $this->fourthLine3Text = $fourthLine3Text;
    }

    public function getFourthLine3Text(): ?string {
        return $this->fourthLine3Text;
    }

    public function setSecondLine1(?Product $secondLine1): void {
        $this->secondLine1 = $secondLine1;
    }

    public function getSecondLine1(): Product {
        return $this->secondLine1;
    }

    public function setThirdLine1(?Product $thirdLine1): void {
        $this->thirdLine1 = $thirdLine1;
    }

    public function getThirdLine1(): Product {
        return $this->thirdLine1;
    }

    public function setModified(?\DateTime $modified): void {
        $this->modified = $modified;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function setSecondLine2ImgFile(?UploadedFile $imgFile): void {
        $this->secondLine2ImgFile = $imgFile;
        $this->refreshUpdated();
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

    public function setFourthLine2ImgFile(?UploadedFile $imgFile): void {
        $this->fourthLine2ImgFile = $imgFile;
        $this->refreshUpdated();
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

    public function setFourthLine3ImgFile(?UploadedFile $imgFile): void {
        $this->fourthLine3ImgFile = $imgFile;
        $this->refreshUpdated();
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
