<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
#[ORM\Table(name: 'property')]
class Property implements \Stringable
{
    public const string NAME_BESCHREIBUNG = 'Beschreibung';
    public const string NAME_FARBEPALETTE = 'Farbepalette';
    public const string NAME_MODEL = 'Model';
    public const string NAME_FARBE = 'Farbe';
    public const string NAME_GLAS = 'Glas';
    public const string NAME_GRIFF = 'Griff';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column]
    public string $name;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    /** @var Collection<CategoryProperty> */
    #[ORM\OneToMany(targetEntity: CategoryProperty::class, mappedBy: 'property')]
    private Collection $categoryProperties;

    /** @var Collection<PropertySet> */
    #[ORM\OneToMany(targetEntity: PropertySet::class, mappedBy: 'property')]
    private Collection $propertySets;

    public function __construct() {
        $this->categoryProperties = new ArrayCollection();
        $this->propertySets = new ArrayCollection();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function addCategoryProperty(CategoryProperty $categoryProperty): void {
        $this->categoryProperties[] = $categoryProperty;
    }

    public function removeCategoryProperty(CategoryProperty $categoryProperty): void {
        $this->categoryProperties->removeElement($categoryProperty);
    }

    /** @return Collection<CategoryProperty> */
    public function getCategoryProperties(): Collection {
        return $this->categoryProperties;
    }

    public function addPropertySet(PropertySet $propertySet): void {
        $this->propertySets[] = $propertySet;
    }

    public function removePropertySet(PropertySet $propertySet): void {
        $this->propertySets->removeElement($propertySet);
    }

    /** @return Collection<PropertySet> */
    public function getPropertySets(): Collection {
        return $this->propertySets;
    }

    public function __toString(): string {
        return $this->name ?? 'Property';
    }
}
