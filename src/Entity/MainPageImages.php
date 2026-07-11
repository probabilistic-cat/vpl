<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
#[ORM\Table(name: 'main_page_images')]
class MainPageImages extends BaseEntity
{
    use IdField;
    use TimestampFields;

    public const string IMAGE_FOLDER = 'img/main_page';
    public const string IMAGE_NAME_PREFIX = 'first_line_1_img_';

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $img = null;

    #[ORM\Column(nullable: true)]
    public ?string $header = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $text = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    public ?UploadedFile $imgFile = null {
        set {
            $this->imgFile = $value;
            $this->modifiedNow();
        }
    }

    #[\Override]
    public function getImagePaths(): array {
        return [$this->img];
    }
}
