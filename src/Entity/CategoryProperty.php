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

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public int $seq;

    #[ORM\Column(type: Types::SMALLINT, options: ['default' => 0, 'unsigned' => true])]
    public int $layer = 0;

    #[ORM\Column(options: ['default' => true])]
    public bool $active = true;

    #[ORM\ManyToOne(targetEntity: Category::class, cascade: ['persist'], inversedBy: 'categoryProperties')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    public Category $category;

    #[ORM\ManyToOne(targetEntity: Property::class, inversedBy: 'categoryProperties')]
    #[ORM\JoinColumn(name: 'property_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    public Property $property;

    /** @var Collection<ProductProperty> */
    #[ORM\OneToMany(targetEntity: ProductProperty::class, mappedBy: 'categoryProperty')]
    #[ORM\OrderBy(['seq' => 'ASC'])]
    private(set) Collection $productProperties;

    public function __construct() {
        $this->productProperties = new ArrayCollection();
    }

    public function addProductProperty(ProductProperty $productProperty): void {
        $this->productProperties[] = $productProperty;
    }

    public function removeProductProperty(ProductProperty $productProperty): void {
        $this->productProperties->removeElement($productProperty);
    }
}
