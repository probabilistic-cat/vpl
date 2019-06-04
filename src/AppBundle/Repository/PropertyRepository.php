<?php

namespace AppBundle\Repository;

use AppBundle\Entity;

class PropertyRepository extends \Doctrine\ORM\EntityRepository
{
    public function createPropertyWithoutDescQueryBuilder()
    {
        return $this->createQueryBuilder('p')
            ->where('p.name != :propNameBeschreibung')
            ->setParameter('propNameBeschreibung', Entity\Property::NAME_BESCHREIBUNG);
    }
}
