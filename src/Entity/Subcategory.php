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
#[ORM\Table(name: 'subcategory')]
#[ORM\Index(name: 'ix__subcategory__category_id', columns: ['category_id'])]
#[ORM\HasLifecycleCallbacks]
class Subcategory
{
    private const string IMG_FOLDER = 'img/subcategory/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column]
    public string $name;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $img = null;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'subcategories')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: false)]
    public Category $category;

    /** @var Collection<Product> */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'subcategory')]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private Collection $products;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modified = new \DateTime();
        }
    }

    public function __construct() {
        $this->products = new ArrayCollection();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function addProduct(Product $product): void {
        $this->products[] = $product;
    }

    public function removeProduct(Product $product): void {
        $this->products->removeElement($product);
    }

    /** @return Collection<Product> */
    public function getProducts(): Collection {
        return $this->products;
    }

    public function uploadImgFile(): void {
        if (!($this->imgFile instanceof UploadedFile)) {
            return;
        }

        $category = $this->category;

        $extension = $this->imgFile->getClientOriginalExtension();
        $fileName = 'cat_' . $category->getId() . '_subcat_' . md5(uniqid('', true)) . '.' . $extension;
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
