<?php

declare(strict_types=1);

namespace App\Entity;

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
    private const string IMG_FOLDER = 'img/category/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $img = null;

    #[ORM\Column(options: ['default' => '#c9eeff'])]
    private string $color = '#c9eeff';

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    /** @var Collection<Subcategory> */
    #[ORM\OneToMany(targetEntity: Subcategory::class, mappedBy: 'category')]
    private Collection $subcategories;

    /** @var Collection<CategoryProperty> */
    #[ORM\OneToMany(targetEntity: CategoryProperty::class, mappedBy: 'category', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private Collection $categoryProperties;

    private ?UploadedFile $imgFile = null;

    public function __construct() {
        $this->subcategories = new ArrayCollection();
        $this->categoryProperties = new ArrayCollection();
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

    public function setDescription(?string $description = null): self {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setImg(?string $img = null): self {
        $this->img = $img;

        return $this;
    }

    public function getImg(): ?string {
        return $this->img;
    }

    public function setColor(string $color): self {
        $this->color = $color;

        return $this;
    }

    public function getColor(): string {
        return $this->color;
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

    public function addSubcategory(Subcategory $subcategory): self {
        $this->subcategories[] = $subcategory;

        return $this;
    }

    /** @return bool TRUE if this collection contained the specified element, FALSE otherwise */
    public function removeSubcategory(Subcategory $subcategory) {
        return $this->subcategories->removeElement($subcategory);
    }

    /** @return Collection<Subcategory> */
    public function getSubcategories(): Collection {
        return $this->subcategories;
    }

    public function addCategoryProperty(CategoryProperty $categoryProperty): self {
        $categoryProperty->setCategory($this);
        $this->categoryProperties[] = $categoryProperty;

        return $this;
    }

    /** @return bool TRUE if this collection contained the specified element, FALSE otherwise */
    public function removeCategoryProperty(CategoryProperty $categoryProperty) {
        return $this->categoryProperties->removeElement($categoryProperty);
    }

    /** @return Collection<CategoryProperty> */
    public function getCategoryProperties(): Collection {
        return $this->categoryProperties;
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
        $fileName = 'cat_' . md5(uniqid('', true)) . '.' . $extension;
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
