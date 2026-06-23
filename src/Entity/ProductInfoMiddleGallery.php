<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Field\IdField;
use App\Entity\Field\TimestampFields;
use App\Helper\FileHelper;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity()]
#[ORM\Table(name: 'product_info_middle_gallery')]
#[ORM\Index(name: 'ix__product_info_m_gal__product_info_m_id', columns: ['product_info_middle_id'])]
#[ORM\HasLifecycleCallbacks]
class ProductInfoMiddleGallery implements \Stringable
{
    use IdField;
    use TimestampFields;

    private const string IMG_FOLDER = 'img/product_gallery/';

    #[ORM\Column(type: Types::TEXT, length: 65535)]
    public string $img;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\ManyToOne(targetEntity: ProductInfoMiddle::class, cascade: ['persist'], inversedBy: 'productInfoMiddleGalleries')]
    #[ORM\JoinColumn(name: 'product_info_middle_id', referencedColumnName: 'id', nullable: false)]
    public ProductInfoMiddle $productInfoMiddle;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modified = new \DateTime();
        }
    }

    public function __toString(): string {
        return 'Gallery';
    }

    public function uploadImgFile(): void {
        if (!($this->imgFile instanceof UploadedFile)) {
            return;
        }

        $info = $this->productInfoMiddle;
        $product = $info->product;
        $subcategory = $product->subcategory;
        $category = $subcategory->category;

        $extension = $this->imgFile->getClientOriginalExtension();
        $fileName = 'cat_' . $category->getId() . '_subcat_' . $subcategory->getId() . '_prod_' . $product->getId()
            . '_info_' . $info->getId() . '_gal_' . md5(uniqid('', true)) . '.' . $extension;
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
        if (file_exists(FileHelper::DIR_PUBLIC . $this->img)) {
            @unlink(FileHelper::DIR_PUBLIC . $this->img);
        }
    }
}
