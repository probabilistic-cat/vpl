<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Property;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class PropertyRepository extends EntityRepository
{
    public function createPropertyWithoutDescQueryBuilder(): QueryBuilder {
        return $this->createQueryBuilder('p')
            ->where('p.name != :propNameBeschreibung')
            ->setParameter('propNameBeschreibung', Property::NAME_BESCHREIBUNG);
    }
}
