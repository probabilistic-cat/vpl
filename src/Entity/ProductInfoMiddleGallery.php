<?php

declare(strict_types=1);

namespace App\Entity;

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
    private const string IMG_FOLDER = 'img/product_gallery/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(type: Types::TEXT, length: 65535)]
    private string $img;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $seq;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: ProductInfoMiddle::class, cascade: ['persist'], inversedBy: 'productInfoMiddleGalleries')]
    #[ORM\JoinColumn(name: 'product_info_middle_id', referencedColumnName: 'id', nullable: false)]
    private ProductInfoMiddle $productInfoMiddle;

    private ?UploadedFile $imgFile = null;

    public function getId(): int {
        return $this->id;
    }

    public function setImg(string $img): self {
        $this->img = $img;

        return $this;
    }

    public function getImg(): string {
        return $this->img;
    }

    public function setSeq(int $seq): self {
        $this->seq = $seq;

        return $this;
    }

    public function getSeq(): int {
        return $this->seq;
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

    public function setProductInfoMiddle(?ProductInfoMiddle $productInfoMiddle = null): self {
        $this->productInfoMiddle = $productInfoMiddle;

        return $this;
    }

    public function getProductInfoMiddle(): ProductInfoMiddle {
        return $this->productInfoMiddle;
    }

    public function __toString(): string {
        return 'Gallery';
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

        $info = $this->getProductInfoMiddle();
        $product = $info->getProduct();
        $subcategory = $product->getSubcategory();
        $category = $subcategory->getCategory();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'cat_' . $category->getId() . '_subcat_' . $subcategory->getId() . '_prod_' . $product->getId()
            . '_info_' . $info->getId() . '_gal_' . md5(uniqid('', true)) . '.' . $extension;
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
        if (file_exists(FileHelper::DIR_PUBLIC . $img)) {
            @unlink(FileHelper::DIR_PUBLIC . $img);
        }
    }
}
