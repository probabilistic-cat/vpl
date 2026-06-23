<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Field\IdField;
use App\Entity\Field\TimestampFields;
use App\Helper\FileHelper;
use App\Repository\ManufacturerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: ManufacturerRepository::class)]
#[ORM\Table(name: 'manufacturer')]
#[ORM\HasLifecycleCallbacks]
class Manufacturer
{
    use IdField;
    use TimestampFields;

    private const string IMG_FOLDER = 'img/manufacturer/';

    #[ORM\Column]
    public string $name;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $img = null;

    /** @var Collection<ProductManufacturer> */
    #[ORM\OneToMany(targetEntity: ProductManufacturer::class, mappedBy: 'manufacturer', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private(set) Collection $productManufacturers;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modified = new \DateTime();
        }
    }

    public function __construct() {
        $this->productManufacturers = new ArrayCollection();
    }

    public function addProductManufacturer(ProductManufacturer $productManufacturer): void {
        $productManufacturer->manufacturer = $this;
        $this->productManufacturers[] = $productManufacturer;
    }

    public function removeProductManufacturer(ProductManufacturer $productManufacturer): void {
        $this->productManufacturers->removeElement($productManufacturer);
    }

    public function uploadImgFile(): void {
        if (!($this->imgFile instanceof UploadedFile)) {
            return;
        }

        $extension = $this->imgFile->getClientOriginalExtension();
        $fileName = 'manuf_' . md5(uniqid('', true)) . '.' . $extension;
        $this->imgFile->move(FileHelper::DIR_PUBLIC . self::IMG_FOLDER, $fileName);
        $this->img = self::IMG_FOLDER . $fileName;
        $this->imgFile = null;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function lifecycleImgFileUpload(): void {
        $this->uploadImgFile();
    }

    #[ORM\PostRemove]
    public function removeImage(): void {
        if (($this->img !== null) && file_exists(FileHelper::DIR_PUBLIC . $this->img)) {
            @unlink(FileHelper::DIR_PUBLIC . $this->img);
        }
    }
}
