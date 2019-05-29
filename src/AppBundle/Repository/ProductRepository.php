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

    public function findByManufacturerId($manufacturerId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p')
            ->from('AppBundle:Product', 'p')
            ->innerjoin('p.productManufacturers', 'pm')
            ->where('pm.manufacturer = :manufacturerId')
            ->orderBy('p.id', 'ASC')
            ->setParameter('manufacturerId', $manufacturerId);

        $products = $qb->getQuery()->getResult();

        return $products;
    }
}
