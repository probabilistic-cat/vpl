<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\ImgFunctions;
use App\Entity\Common\TimestampFields;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
#[ORM\Table(name: 'subcategory')]
#[ORM\Index(name: 'ix__subcategory__category_id', columns: ['category_id'])]
#[ORM\HasLifecycleCallbacks]
class Subcategory
{
    use IdField;
    use ImgFunctions;
    use TimestampFields;

    private const string IMG_FOLDER_NAME = 'subcategory';

    #[ORM\Column]
    public string $name;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $img = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'subcategories')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: false)]
    public Category $category;

    /** @var Collection<Product> */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'subcategory', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private(set) Collection $products;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modifiedNow();
        }
    }

    public function __construct() {
        $this->products = new ArrayCollection();
    }

    public function addProduct(Product $product): void {
        if (!$this->products->contains($product)) {
            $product->subcategory = $this;
            $this->products->add($product);
        }
    }

    public function removeProduct(Product $product): void {
        $this->products->removeElement($product);
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function prePersistUpdateImg(): void {
        self::uploadImgFile($this->imgFile, self::IMG_FOLDER_NAME, function (string $img): void {
            $this->img = $img;
            $this->imgFile = null;
        });
    }

    #[ORM\PostRemove]
    public function postRemoveImg(): void {
        self::deleteImage($this->img);
    }
}
