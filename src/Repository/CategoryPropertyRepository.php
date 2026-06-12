<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Property;
use Doctrine\ORM\EntityRepository;

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
