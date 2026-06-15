<?php

declare(strict_types=1);

namespace App\Entity;

use App\Helper\FileHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
#[ORM\Table(name: 'manufacturer')]
#[ORM\HasLifecycleCallbacks]
class Manufacturer
{
    private const string IMG_FOLDER = 'img/manufacturer/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $img = null;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    /** @var Collection<ProductManufacturer> */
    #[ORM\OneToMany(mappedBy: 'manufacturer', targetEntity: ProductManufacturer::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private Collection $productManufacturers;

    private ?UploadedFile $imgFile = null;

    public function __construct() {
        $this->productManufacturers = new ArrayCollection();
    }

    public function getId(): int {
        return $this->id;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setImg(?string $img = null): self {
        $this->img = $img;

        return $this;
    }

    public function getImg(): ?string {
        return $this->img;
    }

    public function setCreated(\DateTime $created): self {
        $this->created = $created;

        return $this;
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function setModified(?\DateTime $modified = null): self {
        $this->modified = $modified;

        return $this;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function addProductManufacturer(ProductManufacturer $productManufacturer): self {
        $productManufacturer->setManufacturer($this);
        $this->productManufacturers[] = $productManufacturer;

        return $this;
    }

    /** @return bool TRUE if this collection contained the specified element, FALSE otherwise */
    public function removeProductManufacturer(ProductManufacturer $productManufacturer) {
        return $this->productManufacturers->removeElement($productManufacturer);
    }

    /** @return Collection<ProductManufacturer> */
    public function getProductManufacturers(): Collection {
        return $this->productManufacturers;
    }

    public function setImgFile(?UploadedFile $imgFile = null): self {
        $this->imgFile = $imgFile;
        $this->refreshUpdated();

        return $this;
    }

    public function getImgFile(): ?UploadedFile {
        return $this->imgFile;
    }

    public function uploadImgFile(): void {
        if (!$this->getImgFile() instanceof UploadedFile) {
            return;
        }

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'manuf_' . md5(uniqid('', true)) . '.' . $extension;
        $this->getImgFile()->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->setImg(self::IMG_FOLDER . $fileName);
        $this->setImgFile(null);
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function lifecycleImgFileUpload(): void {
        $this->uploadImgFile();
    }

    public function refreshUpdated(): void {
        $this->setModified(new \DateTime());
    }

    #[ORM\PostRemove]
    public function removeImage(): void {
        $img = $this->getImg();
        if (($img !== null) && file_exists(FileHelper::DIR_PUBLIC . $img)) {
            @unlink(FileHelper::DIR_PUBLIC . $img);
        }
    }
}
