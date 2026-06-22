<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'property_set')]
#[ORM\Index(name: 'ix__property_set__property_id', columns: ['property_id'])]
class PropertySet implements \Stringable
{
    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column]
    public string $name;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne(targetEntity: Property::class, cascade: ['persist'], inversedBy: 'propertySets')]
    #[ORM\JoinColumn(name: 'property_id', referencedColumnName: 'id', nullable: false)]
    public Property $property;

    /** @var Collection<PropertyItem> */
    #[ORM\OneToMany(targetEntity: PropertyItem::class, mappedBy: 'propertySet', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $propertyItems;

    /** @var Collection<ProductProperty> */
    #[ORM\OneToMany(targetEntity: ProductProperty::class, mappedBy: 'propertySet')]
    private Collection $productProperties;

    public function __construct() {
        $this->propertyItems = new ArrayCollection();
        $this->productProperties = new ArrayCollection();
    }

    public function __clone() {
        $this->id = null;

        foreach ($this->propertyItems as $propertyItem) {
            $clonedPropertyItem = clone $propertyItem;
            $this->addPropertyItem($clonedPropertyItem);
            $clonedPropertyItem->afterClone();
        }
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function addPropertyItem(PropertyItem $propertyItem): void {
        $propertyItem->propertySet = $this;
        $this->propertyItems[] = $propertyItem;
    }

    public function removePropertyItem(PropertyItem $propertyItem): void {
        $this->propertyItems->removeElement($propertyItem);
    }

    /** @return Collection<PropertyItem> */
    public function getPropertyItems(): Collection {
        return $this->propertyItems;
    }

    public function addProductProperty(ProductProperty $productProperty): void {
        $this->productProperties[] = $productProperty;
    }

    public function removeProductProperty(ProductProperty $productProperty): void {
        $this->productProperties->removeElement($productProperty);
    }

    /** @return Collection<ProductProperty> */
    public function getProductProperties(): Collection {
        return $this->productProperties;
    }

    public function __toString(): string {
        return $this->name ?? 'PropertySet';
    }
}
