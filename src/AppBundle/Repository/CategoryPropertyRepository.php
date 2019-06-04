<?php

namespace AppBundle\Repository;

use AppBundle\Entity;

class CategoryPropertyRepository extends \Doctrine\ORM\EntityRepository
{
    public function createCategoryQueryBuilder(Entity\Category $category)
    {
        return $this->createQueryBuilder('cp')
            ->innerJoin('cp.property', 'p')
            ->where('cp.category = :categoryId AND p.name != :propNameBeschreibung')
            ->setParameter('categoryId', $category->getId())
            ->setParameter('propNameBeschreibung', Entity\Property::NAME_BESCHREIBUNG);
    }
}
