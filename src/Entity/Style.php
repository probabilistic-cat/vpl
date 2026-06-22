<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'style')]
class Style implements \Stringable
{
    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $seq;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    /** @var Collection<StyleImg> */
    #[ORM\OneToMany(targetEntity: StyleImg::class, mappedBy: 'style', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private Collection $styleImgs;

    /** @var Collection<StyleInfoBottom> */
    #[ORM\OneToMany(targetEntity: StyleInfoBottom::class, mappedBy: 'style', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private Collection $styleInfoBottoms;

    public function __construct() {
        $this->styleImgs = new ArrayCollection();
        $this->styleInfoBottoms = new ArrayCollection();
    }

    public function getId(): int {
        return $this->id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
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

    public function addStyleImg(StyleImg $styleImg): void {
        $styleImg->setStyle($this);
        $this->styleImgs[] = $styleImg;
    }

    public function removeStyleImg(StyleImg $styleImg): void {
        $this->styleImgs->removeElement($styleImg);
    }

    /** @return Collection<StyleImg> */
    public function getStyleImgs(): Collection {
        return $this->styleImgs;
    }

    public function addStyleInfoBottom(StyleInfoBottom $styleInfoBottom): void {
        $styleInfoBottom->setStyle($this);
        $this->styleInfoBottoms[] = $styleInfoBottom;
    }

    public function removeStyleInfoBottom(StyleInfoBottom $styleInfoBottom): void {
        $this->styleInfoBottoms->removeElement($styleInfoBottom);
    }

    /** @return Collection<StyleInfoBottom> */
    public function getStyleInfoBottoms(): Collection {
        return $this->styleInfoBottoms;
    }

    public function __toString(): string {
        return $this->name ?? 'Style';
    }
}
