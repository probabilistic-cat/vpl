<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use App\Repository\ManufacturerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: ManufacturerRepository::class)]
#[ORM\Table(name: 'manufacturer')]
class Manufacturer extends BaseEntity
{
    use IdField;
    use TimestampFields;

    public const string IMAGE_FOLDER = 'img/manufacturer';
    public const string IMAGE_NAME_PREFIX = 'manufacturer';

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
            $this->modifiedNow();
        }
    }

    public function __construct() {
        $this->productManufacturers = new ArrayCollection();
    }

    public function addProductManufacturer(ProductManufacturer $productManufacturer): void {
        if (!$this->productManufacturers->contains($productManufacturer)) {
            $productManufacturer->manufacturer = $this;
            $this->productManufacturers->add($productManufacturer);
        }
    }

    public function removeProductManufacturer(ProductManufacturer $productManufacturer): void {
        $this->productManufacturers->removeElement($productManufacturer);
    }

    #[\Override]
    public function getImagePaths(): array {
        return [$this->img];
    }
}
