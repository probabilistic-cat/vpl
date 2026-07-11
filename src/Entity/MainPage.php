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
class MainPage extends BaseEntity
{
    public const string IMAGE_FOLDER = 'img/main_page';
    public const string IMAGE_SECOND2_NAME_PREFIX = 'second_line_2_img';
    public const string IMAGE_FOURTH2_NAME_PREFIX = 'fourth_line_2_img';
    public const string IMAGE_FOURTH3_NAME_PREFIX = 'fourth_line_3_img';

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

    #[\Override]
    public function getImagePaths(): array {
        return [$this->secondLine2Img, $this->fourthLine2Img, $this->fourthLine3Img];
    }
}
