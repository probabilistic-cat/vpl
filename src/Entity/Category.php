<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Field\IdField;
use App\Entity\Field\TimestampFields;
use App\Helper\FileHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity()]
#[ORM\Table(name: 'category')]
#[ORM\HasLifecycleCallbacks]
class Category
{
    use IdField;
    use TimestampFields;

    private const string IMG_FOLDER = 'img/category/';

    #[ORM\Column]
    public string $name;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $img = null;

    #[ORM\Column(options: ['default' => '#c9eeff'])]
    public string $color = '#c9eeff';

    /** @var Collection<Subcategory> */
    #[ORM\OneToMany(targetEntity: Subcategory::class, mappedBy: 'category')]
    private Collection $subcategories;

    /** @var Collection<CategoryProperty> */
    #[ORM\OneToMany(targetEntity: CategoryProperty::class, mappedBy: 'category', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private Collection $categoryProperties;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modified = new \DateTime();
        }
    }

    public function __construct() {
        $this->subcategories = new ArrayCollection();
        $this->categoryProperties = new ArrayCollection();
    }

    public function addSubcategory(Subcategory $subcategory): void {
        $this->subcategories[] = $subcategory;
    }

    public function removeSubcategory(Subcategory $subcategory): void {
        $this->subcategories->removeElement($subcategory);
    }

    /** @return Collection<Subcategory> */
    public function getSubcategories(): Collection {
        return $this->subcategories;
    }

    public function addCategoryProperty(CategoryProperty $categoryProperty): void {
        $categoryProperty->category = $this;
        $this->categoryProperties[] = $categoryProperty;
    }

    public function removeCategoryProperty(CategoryProperty $categoryProperty): void {
        $this->categoryProperties->removeElement($categoryProperty);
    }

    /** @return Collection<CategoryProperty> */
    public function getCategoryProperties(): Collection {
        return $this->categoryProperties;
    }

    public function uploadImgFile(): void {
        if (!($this->imgFile instanceof UploadedFile)) {
            return;
        }

        $extension = $this->imgFile->getClientOriginalExtension();
        $fileName = 'cat_' . md5(uniqid('', true)) . '.' . $extension;
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
