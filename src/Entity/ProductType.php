<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use App\Helper\FileHelper;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity()]
#[ORM\Table(name: 'product_type')]
#[ORM\Index(name: 'ix__product_type__product_id', columns: ['product_id'])]
#[ORM\HasLifecycleCallbacks]
class ProductType implements \Stringable
{
    use IdField;
    use TimestampFields;

    private const string IMG_FOLDER = 'img/product_type/';

    #[ORM\Column]
    public string $text;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $img = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productTypes')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false)]
    public Product $product;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modified = new \DateTime();
        }
    }

    public function __toString(): string {
        return 'ProductType';
    }

    public function uploadImgFile(): void {
        if (!($this->imgFile instanceof UploadedFile)) {
            return;
        }

        $product = $this->product;
        $subcategory = $product->subcategory;
        $category = $subcategory->category;

        $extension = $this->imgFile->getClientOriginalExtension();
        $fileName = 'cat_' . $category->id . '_subcat_' . $subcategory->id . '_prod_' . $product->id
            . '_type_' . md5(uniqid('', true)) . '.' . $extension;
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
