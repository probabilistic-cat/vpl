<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="style")
 * @ORM\Entity
 */
class Style implements \Stringable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="seq", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $seq;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false, options={"default"="2000-01-01 00:00:00"})
     */
    private $created;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="StyleImg", mappedBy="style", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"seq" = "ASC"})
     */
    private $styleImgs;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="StyleInfoBottom", mappedBy="style", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"seq" = "ASC"})
     */
    private $styleInfoBottoms;

    /**
     * Constructor
     */
    public function __construct() {
        $this->styleImgs = new ArrayCollection();
        $this->styleInfoBottoms = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name): self {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param int $seq
     */
    public function setSeq($seq): self {
        $this->seq = $seq;

        return $this;
    }

    /**
     * @return int
     */
    public function getSeq() {
        return $this->seq;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created): self {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param \DateTime|null $modified
     */
    public function setModified($modified = null): self {
        $this->modified = $modified;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getModified() {
        return $this->modified;
    }

    public function addStyleImg(StyleImg $styleImg): self {
        $styleImg->setStyle($this);
        $this->styleImgs[] = $styleImg;

        return $this;
    }

    /**
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeStyleImg(StyleImg $styleImg) {
        return $this->styleImgs->removeElement($styleImg);
    }

    /**
     * @return Collection
     */
    public function getStyleImgs() {
        return $this->styleImgs;
    }

    public function addStyleInfoBottom(StyleInfoBottom $styleInfoBottom): self {
        $styleInfoBottom->setStyle($this);
        $this->styleInfoBottoms[] = $styleInfoBottom;

        return $this;
    }

    /**
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeStyleInfoBottom(StyleInfoBottom $styleInfoBottom) {
        return $this->styleInfoBottoms->removeElement($styleInfoBottom);
    }

    /**
     * @return Collection
     */
    public function getStyleInfoBottoms() {
        return $this->styleInfoBottoms;
    }

    public function __toString(): string {
        return $this->name ?? 'Style';
    }
}
