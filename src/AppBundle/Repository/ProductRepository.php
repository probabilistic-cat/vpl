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

    public function findBySubcategoryManufacturer(int $subcategoryId, int $manufacturerId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p')
            ->from('AppBundle:Product', 'p')
            ->innerjoin('p.productManufacturers', 'pm')
            ->where('p.subcategory = :subcategoryId AND pm.manufacturer = :manufacturerId')
            ->orderBy('p.id', 'ASC')
            ->setParameter('subcategoryId', $subcategoryId)
            ->setParameter('manufacturerId', $manufacturerId);

        $products = $qb->getQuery()->getResult();

        return $products;
    }

    public function findBySubcategory(int $subcategoryId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p')
            ->from('AppBundle:Product', 'p')
            ->where('p.subcategory = :subcategoryId')
            ->orderBy('p.id', 'ASC')
            ->setParameter('subcategoryId', $subcategoryId);

        $products = $qb->getQuery()->getResult();

        return $products;
    }
}
