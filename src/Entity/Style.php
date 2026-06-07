<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="style")
 * @ORM\Entity
 */
class Style
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="StyleImg", mappedBy="style", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"seq" = "ASC"})
     */
    private $styleImgs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="StyleInfoBottom", mappedBy="style", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"seq" = "ASC"})
     */
    private $styleInfoBottoms;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->styleImgs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->styleInfoBottoms = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return Style
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $seq
     * @return Style
     */
    public function setSeq($seq)
    {
        $this->seq = $seq;

        return $this;
    }

    /**
     * @return int
     */
    public function getSeq()
    {
        return $this->seq;
    }

    /**
     * @param \DateTime $created
     * @return Style
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime|null $modified
     * @return Style
     */
    public function setModified($modified = null)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param \App\Entity\StyleImg $styleImg
     * @return Style
     */
    public function addStyleImg(\App\Entity\StyleImg $styleImg)
    {
        $styleImg->setStyle($this);
        $this->styleImgs[] = $styleImg;

        return $this;
    }

    /**
     * @param \App\Entity\StyleImg $styleImg
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeStyleImg(\App\Entity\StyleImg $styleImg)
    {
        return $this->styleImgs->removeElement($styleImg);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStyleImgs()
    {
        return $this->styleImgs;
    }

    /**
     * @param \App\Entity\StyleInfoBottom $styleInfoBottom
     * @return Style
     */
    public function addStyleInfoBottom(\App\Entity\StyleInfoBottom $styleInfoBottom)
    {
        $styleInfoBottom->setStyle($this);
        $this->styleInfoBottoms[] = $styleInfoBottom;

        return $this;
    }

    /**
     * @param \App\Entity\StyleInfoBottom $styleImg
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeStyleInfoBottom(\App\Entity\StyleInfoBottom $styleInfoBottom)
    {
        return $this->styleInfoBottoms->removeElement($styleInfoBottom);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStyleInfoBottoms()
    {
        return $this->styleInfoBottoms;
    }

    public function __toString()
    {
        return $this->name ?? 'Style';;
    }
}
