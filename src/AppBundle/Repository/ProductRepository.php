<?php

namespace AppBundle\Repository;

class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM AppBundle:Product p ORDER BY p.name ASC'
            )
            ->getResult();
    }
}
