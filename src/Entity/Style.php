<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'style')]
class Style implements \Stringable
{
    use IdField;
    use TimestampFields;

    #[ORM\Column]
    public string $name;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    /** @var Collection<StyleImg> */
    #[ORM\OneToMany(targetEntity: StyleImg::class, mappedBy: 'style', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private(set) Collection $styleImgs;

    /** @var Collection<StyleInfoBottom> */
    #[ORM\OneToMany(targetEntity: StyleInfoBottom::class, mappedBy: 'style', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private(set) Collection $styleInfoBottoms;

    public function __construct() {
        $this->styleImgs = new ArrayCollection();
        $this->styleInfoBottoms = new ArrayCollection();
    }

    public function addStyleImg(StyleImg $styleImg): void {
        $styleImg->style = $this;
        $this->styleImgs[] = $styleImg;
    }

    public function removeStyleImg(StyleImg $styleImg): void {
        $this->styleImgs->removeElement($styleImg);
    }

    public function addStyleInfoBottom(StyleInfoBottom $styleInfoBottom): void {
        $styleInfoBottom->style = $this;
        $this->styleInfoBottoms[] = $styleInfoBottom;
    }

    public function removeStyleInfoBottom(StyleInfoBottom $styleInfoBottom): void {
        $this->styleInfoBottoms->removeElement($styleInfoBottom);
    }

    public function __toString(): string {
        return $this->name ?? 'Style';
    }
}
