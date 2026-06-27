<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use App\Repository\CategoryPropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryPropertyRepository::class)]
#[ORM\Table(name: 'category_property')]
#[ORM\Index(name: 'ix__category_property__category_id', columns: ['category_id'])]
#[ORM\Index(name: 'ix__category_property__property_id', columns: ['property_id'])]
class CategoryProperty
{
    use IdField;
    use TimestampFields;

    private const bool ACTIVE_DEFAULT = true;
    private const int LAYER_DEFAULT = 0;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\Column(type: Types::SMALLINT, options: ['default' => self::LAYER_DEFAULT, 'unsigned' => true])]
    public int $layer = self::LAYER_DEFAULT;

    #[ORM\Column(options: ['default' => self::ACTIVE_DEFAULT])]
    public bool $active = self::ACTIVE_DEFAULT;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'categoryProperties')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: false)]
    public Category $category;

    #[ORM\ManyToOne(targetEntity: Property::class, inversedBy: 'categoryProperties')]
    #[ORM\JoinColumn(name: 'property_id', referencedColumnName: 'id', nullable: false)]
    public Property $property;

    /** @var Collection<ProductProperty> */
    #[ORM\OneToMany(targetEntity: ProductProperty::class, mappedBy: 'categoryProperty', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private(set) Collection $productProperties;

    public function __construct() {
        $this->productProperties = new ArrayCollection();
    }

    public function addProductProperty(ProductProperty $productProperty): void {
        if (!$this->productProperties->contains($productProperty)) {
            $productProperty->categoryProperty = $this;
            $this->productProperties->add($productProperty);
        }
    }

    public function removeProductProperty(ProductProperty $productProperty): void {
        $this->productProperties->removeElement($productProperty);
    }
}
