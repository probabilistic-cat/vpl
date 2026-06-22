<?php

declare(strict_types=1);

namespace App\Entity;

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
    private const string IMG_FOLDER = 'img/product_type/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column]
    private string $text;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $img = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $seq;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: Product::class, cascade: ['persist'], inversedBy: 'productTypes')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false)]
    private Product $product;

    private ?UploadedFile $imgFile = null;

    public function getId(): int {
        return $this->id;
    }

    public function setText(string $text): void {
        $this->text = $text;
    }

    public function getText(): string {
        return $this->text;
    }

    public function setImg(?string $img): void {
        $this->img = $img;
    }

    public function getImg(): ?string {
        return $this->img;
    }

    public function setSeq(int $seq): void {
        $this->seq = $seq;
    }

    public function getSeq(): int {
        return $this->seq;
    }

    public function setCreated(\DateTime $created): void {
        $this->created = $created;
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function setModified(?\DateTime $modified): void {
        $this->modified = $modified;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function setProduct(?Product $product): void {
        $this->product = $product;
    }

    public function getProduct(): Product {
        return $this->product;
    }

    public function __toString(): string {
        return 'ProductType';
    }

    public function setImgFile(?UploadedFile $imgFile): void {
        $this->imgFile = $imgFile;
        $this->refreshUpdated();
    }

    public function getImgFile(): ?UploadedFile {
        return $this->imgFile;
    }

    public function uploadImgFile(): void {
        if (!$this->getImgFile() instanceof UploadedFile) {
            return;
        }

        $product = $this->getProduct();
        $subcategory = $product->getSubcategory();
        $category = $subcategory->getCategory();

        $extension = $this->getImgFile()->getClientOriginalExtension();
        $fileName = 'cat_' . $category->getId() . '_subcat_' . $subcategory->getId() . '_prod_' . $product->getId()
            . '_type_' . md5(uniqid('', true)) . '.' . $extension;
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
