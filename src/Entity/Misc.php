<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MiscRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: MiscRepository::class)]
#[ORM\Table(name: 'misc')]
#[ORM\HasLifecycleCallbacks]
class Misc
{
    private const string IMG_FOLDER = 'img/misc/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column]
    private string $designName;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $designDescription = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $designImg = null;

    #[ORM\Column]
    private string $categoriesName;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $categoriesDescription = null;

    #[ORM\Column(nullable: true)]
    private ?string $contactAddress = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $contactMapSrc = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    private ?UploadedFile $designImgFile = null;

    public function getId(): int {
        return $this->id;
    }

    public function setDesignName(string $designName): void {
        $this->designName = $designName;
    }

    public function getDesignName(): string {
        return $this->designName;
    }

    public function setDesignDescription(?string $designDescription): void {
        $this->designDescription = $designDescription;
    }

    public function getDesignDescription(): ?string {
        return $this->designDescription;
    }

    public function setDesignImg(?string $designImg): void {
        $this->designImg = $designImg;
    }

    public function getDesignImg(): ?string {
        return $this->designImg;
    }

    public function setCategoriesName(string $categoriesName): void {
        $this->categoriesName = $categoriesName;
    }

    public function getCategoriesName(): string {
        return $this->categoriesName;
    }

    public function setCategoriesDescription(?string $categoriesDescription): void {
        $this->categoriesDescription = $categoriesDescription;
    }

    public function getCategoriesDescription(): ?string {
        return $this->categoriesDescription;
    }

    public function setContactAddress(?string $contactAddress): void {
        $this->contactAddress = $contactAddress;
    }

    public function getContactAddress(): ?string {
        return $this->contactAddress;
    }

    public function setContactMapSrc(?string $contactMapSrc): void {
        $this->contactMapSrc = $contactMapSrc;
    }

    public function getContactMapSrc(): ?string {
        return $this->contactMapSrc;
    }

    public function setModified(?\DateTime $modified): void {
        $this->modified = $modified;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function setDesignImgFile(?UploadedFile $designImgFile): void {
        $this->designImgFile = $designImgFile;
        $this->refreshUpdated();
    }

    public function getDesignImgFile(): ?UploadedFile {
        return $this->designImgFile;
    }

    public function uploadDesignImgFile(): void {
        if (!$this->getDesignImgFile() instanceof UploadedFile) {
            return;
        }

        $extension = $this->getDesignImgFile()->getClientOriginalExtension();
        $fileName = 'design_img.' . $extension;
        $this->getDesignImgFile()->move(self::IMG_FOLDER, $fileName);
        $this->setDesignImg(self::IMG_FOLDER . $fileName);
        $this->setDesignImgFile(null);
    }

    #[ORM\PreUpdate]
    public function lifecycleImgFileUpload(): void {
        $this->uploadDesignImgFile();
    }

    public function refreshUpdated(): void {
        $this->setModified(new \DateTime());
    }
}
