<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryPropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryPropertyRepository::class)]
#[ORM\Table(name: 'category_property')]
#[ORM\Index(columns: ['category_id'], name: 'ix__category_property__category_id')]
#[ORM\Index(columns: ['property_id'], name: 'ix__category_property__property_id')]
class CategoryProperty
{
    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $seq;

    #[ORM\Column(type: Types::SMALLINT, options: ['default' => 0, 'unsigned' => true])]
    private int $layer = 0;

    #[ORM\Column(options: ['default' => true])]
    private bool $active = true;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: Category::class, cascade: ['persist'], inversedBy: 'categoryProperties')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: false)]
    private Category $category;

    #[ORM\ManyToOne(targetEntity: Property::class, inversedBy: 'categoryProperties')]
    #[ORM\JoinColumn(name: 'property_id', referencedColumnName: 'id', nullable: false)]
    private Property $property;

    /** @var Collection<ProductProperty> */
    #[ORM\OneToMany(mappedBy: 'categoryProperty', targetEntity: ProductProperty::class)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private Collection $productProperties;

    public function __construct() {
        $this->productProperties = new ArrayCollection();
    }

    public function getId(): int {
        return $this->id;
    }

    public function setSeq(int $seq): self {
        $this->seq = $seq;

        return $this;
    }

    public function getSeq(): int {
        return $this->seq;
    }

    public function setLayer(int $layer): self {
        $this->layer = $layer;

        return $this;
    }

    public function getLayer(): int {
        return $this->layer;
    }

    public function setActive(bool $active): self {
        $this->active = $active;

        return $this;
    }

    public function getActive(): bool {
        return $this->active;
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

    public function setCategory(?Category $category = null): self {
        $this->category = $category;

        return $this;
    }

    public function getCategory(): Category {
        return $this->category;
    }

    public function setProperty(?Property $property = null): self {
        $this->property = $property;

        return $this;
    }

    public function getProperty(): Property {
        return $this->property;
    }

    public function addProductProperty(ProductProperty $productProperty): self {
        $this->productProperties[] = $productProperty;
        return $this;
    }

    /** @return bool TRUE if this collection contained the specified element, FALSE otherwise */
    public function removeProductProperty(ProductProperty $productProperty) {
        return $this->productProperties->removeElement($productProperty);
    }

    /** @return Collection<ProductProperty> */
    public function getProductProperties(): Collection {
        return $this->productProperties;
    }
}
