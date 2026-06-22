<?php

declare(strict_types=1);

namespace App\Entity;

use App\Helper\FileHelper;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity()]
#[ORM\Table(name: 'product_property')]
#[ORM\Index(name: 'ix__product_property__product_id', columns: ['product_id'])]
#[ORM\Index(name: 'ix__product_property__category_property_id', columns: ['category_property_id'])]
#[ORM\Index(name: 'ix__prod_prop_set__property_set_id', columns: ['property_set_id'])]
#[ORM\HasLifecycleCallbacks]
class ProductProperty implements \Stringable
{
    private const string IMG_FOLDER = 'img/product_property/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(nullable: true)]
    public ?string $name = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $img = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: CategoryProperty::class, inversedBy: 'productProperties')]
    #[ORM\JoinColumn(name: 'category_property_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    public CategoryProperty $categoryProperty;

    #[ORM\ManyToOne(targetEntity: Product::class, cascade: ['persist'], inversedBy: 'productProperties')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    public Product $product;

    #[ORM\ManyToOne(targetEntity: PropertySet::class, cascade: ['persist'], inversedBy: 'productProperties')]
    #[ORM\JoinColumn(name: 'property_set_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    public ?PropertySet $propertySet = null;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modified = new \DateTime();
        }
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

    public function __toString(): string {
        return 'ProductProperty';
    }

    public function uploadImgFile(): void {
        if (!($this->imgFile instanceof UploadedFile)) {
            return;
        }

        $product = $this->product;
        $subcategory = $product->subcategory;
        $category = $subcategory->category;
        $categoryProperty = $this->categoryProperty;

        $extension = $this->imgFile->getClientOriginalExtension();
        $fileName = 'cat_' . $category->getId() . '_subcat_' . $subcategory->getId() . '_prod_' . $product->getId()
            . '_cprop_' . $categoryProperty->getId() . '_pprop_' . md5(uniqid('', true)) . '.' . $extension;
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
