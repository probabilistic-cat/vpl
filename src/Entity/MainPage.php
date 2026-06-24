<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MainPageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: MainPageRepository::class)]
#[ORM\Table(name: 'main_page')]
#[ORM\Index(name: 'ix__main_page__third_line_1', columns: ['third_line_1'])]
#[ORM\Index(name: 'ix__main_page__second_line_1', columns: ['second_line_1'])]
#[ORM\HasLifecycleCallbacks]
class MainPage
{
    private const string IMG_FOLDER = 'img/main_page/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    private(set) int $id;

    #[ORM\Column(length: 32, nullable: true)]
    public ?string $phone = null;

    #[ORM\Column(nullable: true)]
    public ?string $mail = null;

    #[ORM\Column(nullable: true)]
    public ?string $facebook = null;

    #[ORM\Column(nullable: true)]
    public ?string $copyright = null;

    #[ORM\Column(name: 'second_line_2_img', type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $secondLine2Img = null;

    #[ORM\Column(name: 'second_line_3_header', nullable: true)]
    public ?string $secondLine3Header = null;

    #[ORM\Column(name: 'second_line_3_text', type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $secondLine3Text = null;

    #[ORM\Column(name: 'fourth_line_1_header', nullable: true)]
    public ?string $fourthLine1Header = null;

    #[ORM\Column(name: 'fourth_line_1_text', type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $fourthLine1Text = null;

    #[ORM\Column(name: 'fourth_line_2_img', type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $fourthLine2Img = null;

    #[ORM\Column(name: 'fourth_line_2_header', nullable: true)]
    public ?string $fourthLine2Header = null;

    #[ORM\Column(name: 'fourth_line_2_text', type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $fourthLine2Text = null;

    #[ORM\Column(name: 'fourth_line_3_img', type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $fourthLine3Img = null;

    #[ORM\Column(name: 'fourth_line_3_header', nullable: true)]
    public ?string $fourthLine3Header = null;

    #[ORM\Column(name: 'fourth_line_3_text', type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $fourthLine3Text = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'second_line_1', referencedColumnName: 'id')]
    public Product $secondLine1;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'third_line_1', referencedColumnName: 'id')]
    public Product $thirdLine1;

    #[ORM\Column(nullable: true)]
    private(set) ?\DateTime $modified = null;

    public ?UploadedFile $secondLine2ImgFile = null {
        set {
            $this->secondLine2ImgFile = $value;
            $this->modified = new \DateTime();
        }
    }

    public ?UploadedFile $fourthLine2ImgFile = null {
        set {
            $this->fourthLine2ImgFile = $value;
            $this->modified = new \DateTime();
        }
    }

    public ?UploadedFile $fourthLine3ImgFile = null {
        set {
            $this->fourthLine3ImgFile = $value;
            $this->modified = new \DateTime();
        }
    }

    public function uploadSecondLine2ImgFile(): void {
        if (!($this->secondLine2ImgFile instanceof UploadedFile)) {
            return;
        }

        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $mainPageId = $this->id !== null ? $this->id : $microTimeStamp;
        $extension = $this->secondLine2ImgFile->getClientOriginalExtension();
        $fileName = 'second_line_2_img_' . $mainPageId . '.' . $extension;
        $this->secondLine2ImgFile->move(self::IMG_FOLDER, $fileName);
        $this->secondLine2Img = self::IMG_FOLDER . $fileName;
        $this->secondLine2ImgFile = null;
    }

    public function uploadFourthLine2ImgFile(): void {
        if (!($this->fourthLine2ImgFile instanceof UploadedFile)) {
            return;
        }

        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $mainPageId = $this->id !== null ? $this->id : $microTimeStamp;
        $extension = $this->fourthLine2ImgFile->getClientOriginalExtension();
        $fileName = 'fourth_line_2_img_' . $mainPageId . '.' . $extension;
        $this->fourthLine2ImgFile->move(self::IMG_FOLDER, $fileName);
        $this->fourthLine2Img = self::IMG_FOLDER . $fileName;
        $this->fourthLine2ImgFile = null;
    }

    public function uploadFourthLine3ImgFile(): void {
        if (!($this->fourthLine3ImgFile instanceof UploadedFile)) {
            return;
        }

        $microTimeStamp = sprintf('%d', round(microtime(true) * 1000000));
        $mainPageId = $this->id !== null ? $this->id : $microTimeStamp;
        $extension = $this->fourthLine3ImgFile->getClientOriginalExtension();
        $fileName = 'fourth_line_3_img_' . $mainPageId . '.' . $extension;
        $this->fourthLine3ImgFile->move(self::IMG_FOLDER, $fileName);
        $this->fourthLine3Img = self::IMG_FOLDER . $fileName;
        $this->fourthLine3ImgFile = null;
    }

    #[ORM\PreUpdate]
    public function lifecycleImgFileUpload(): void {
        $this->uploadSecondLine2ImgFile();
        $this->uploadFourthLine2ImgFile();
        $this->uploadFourthLine3ImgFile();
    }
}
