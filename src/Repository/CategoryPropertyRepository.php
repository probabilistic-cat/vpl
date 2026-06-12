<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Category;
use App\Entity\Property;

class CategoryPropertyRepository extends EntityRepository
{
    public function createCategoryQueryBuilder(Category $category)
    {
        return $this->createQueryBuilder('cp')
            ->innerJoin('cp.property', 'p')
            ->where('cp.category = :categoryId AND p.name != :propNameBeschreibung')
            ->setParameter('categoryId', $category->getId())
            ->setParameter('propNameBeschreibung', Property::NAME_BESCHREIBUNG);
    }
}
