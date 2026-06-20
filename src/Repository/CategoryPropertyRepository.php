<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Property;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class CategoryPropertyRepository extends EntityRepository
{
    public function getQBWithoutDesc(Category $category): QueryBuilder {
        return $this->createQueryBuilder('cp')
            ->innerJoin('cp.property', 'p')
            ->where('cp.category = :category AND p.name != :propNameBeschreibung')
            ->setParameter('category', $category)
            ->setParameter('propNameBeschreibung', Property::NAME_BESCHREIBUNG)
        ;
    }
}
