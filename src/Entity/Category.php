<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
#[ORM\Table(name: 'category')]
class Category extends BaseEntity
{
    use IdField;
    use TimestampFields;

    private const string COLOR_DEFAULT = '#c9eeff';

    public const string IMAGE_FOLDER = 'img/category';
    public const string IMAGE_NAME_PREFIX = 'category';

    #[ORM\Column]
    public string $name;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $img = null;

    #[ORM\Column(options: ['default' => self::COLOR_DEFAULT])]
    public string $color = self::COLOR_DEFAULT;

    /** @var Collection<Subcategory> */
    #[ORM\OneToMany(targetEntity: Subcategory::class, mappedBy: 'category', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private(set) Collection $subcategories;

    /** @var Collection<CategoryProperty> */
    #[ORM\OneToMany(targetEntity: CategoryProperty::class, mappedBy: 'category', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private(set) Collection $categoryProperties;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modifiedNow();
        }
    }

    public function __construct() {
        $this->subcategories = new ArrayCollection();
        $this->categoryProperties = new ArrayCollection();
    }

    public function addSubcategory(Subcategory $subcategory): void {
        if (!$this->subcategories->contains($subcategory)) {
            $subcategory->category = $this;
            $this->subcategories->add($subcategory);
        }
    }

    public function removeSubcategory(Subcategory $subcategory): void {
        $this->subcategories->removeElement($subcategory);
    }

    public function addCategoryProperty(CategoryProperty $categoryProperty): void {
        if (!$this->categoryProperties->contains($categoryProperty)) {
            $categoryProperty->category = $this;
            $this->categoryProperties->add($categoryProperty);
        }
    }

    public function removeCategoryProperty(CategoryProperty $categoryProperty): void {
        $this->categoryProperties->removeElement($categoryProperty);
    }

    #[\Override]
    public function getImagePaths(): array {
        return [$this->img];
    }
}
