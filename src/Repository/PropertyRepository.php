<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Property;

class PropertyRepository extends EntityRepository
{
    public function createPropertyWithoutDescQueryBuilder()
    {
        return $this->createQueryBuilder('p')
            ->where('p.name != :propNameBeschreibung')
            ->setParameter('propNameBeschreibung', Property::NAME_BESCHREIBUNG);
    }
}
