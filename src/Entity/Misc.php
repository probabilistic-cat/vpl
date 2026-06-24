<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MiscRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: MiscRepository::class)]
#[ORM\Table(name: 'misc')]
#[ORM\HasLifecycleCallbacks]
class Misc
{
    private const string IMG_FOLDER = 'img/misc/';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    private(set) int $id;

    #[ORM\Column]
    public string $designName;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $designDescription = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $designImg = null;

    #[ORM\Column]
    public string $categoriesName;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $categoriesDescription = null;

    #[ORM\Column(nullable: true)]
    public ?string $contactAddress = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    public ?string $contactMapSrc = null;

    #[ORM\Column(nullable: true)]
    private(set) ?\DateTime $modified = null;

    public ?UploadedFile $designImgFile = null {
        set {
            $this->designImgFile = $value;
            $this->modified = new \DateTime();
        }
    }

    public function uploadDesignImgFile(): void {
        if (!($this->designImgFile instanceof UploadedFile)) {
            return;
        }

        $extension = $this->designImgFile->getClientOriginalExtension();
        $fileName = 'design_img.' . $extension;
        $this->designImgFile->move(self::IMG_FOLDER, $fileName);
        $this->designImg = self::IMG_FOLDER . $fileName;
        $this->designImgFile = null;
    }

    #[ORM\PreUpdate]
    public function lifecycleImgFileUpload(): void {
        $this->uploadDesignImgFile();
    }
}
