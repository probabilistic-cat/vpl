<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
#[ORM\Table(name: 'property')]
class Property implements \Stringable
{
    use IdField;
    use TimestampFields;

    public const string NAME_BESCHREIBUNG = 'Beschreibung';
    public const string NAME_FARBEPALETTE = 'Farbepalette';
    public const string NAME_MODEL = 'Model';
    public const string NAME_FARBE = 'Farbe';
    public const string NAME_GLAS = 'Glas';
    public const string NAME_GRIFF = 'Griff';

    #[ORM\Column]
    public string $name;

    /** @var Collection<CategoryProperty> */
    #[ORM\OneToMany(targetEntity: CategoryProperty::class, mappedBy: 'property')]
    private(set) Collection $categoryProperties;

    /** @var Collection<PropertySet> */
    #[ORM\OneToMany(targetEntity: PropertySet::class, mappedBy: 'property')]
    private(set) Collection $propertySets;

    public function __construct() {
        $this->categoryProperties = new ArrayCollection();
        $this->propertySets = new ArrayCollection();
    }

    public function addCategoryProperty(CategoryProperty $categoryProperty): void {
        $this->categoryProperties[] = $categoryProperty;
    }

    public function removeCategoryProperty(CategoryProperty $categoryProperty): void {
        $this->categoryProperties->removeElement($categoryProperty);
    }

    public function addPropertySet(PropertySet $propertySet): void {
        $this->propertySets[] = $propertySet;
    }

    public function removePropertySet(PropertySet $propertySet): void {
        $this->propertySets->removeElement($propertySet);
    }

    public function __toString(): string {
        return $this->name ?? 'Property';
    }
}
