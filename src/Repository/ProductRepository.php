<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM App:Product p ORDER BY p.name ASC'
            )
            ->getResult();
    }

    public function findBySubcategoryManufacturer(int $subcategoryId, int $manufacturerId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p')
            ->from('App:Product', 'p')
            ->innerjoin('p.productManufacturers', 'pm')
            ->where('p.subcategory = :subcategoryId AND pm.manufacturer = :manufacturerId')
            ->orderBy('p.seq', 'ASC')
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
            ->from('App:Product', 'p')
            ->where('p.subcategory = :subcategoryId')
            ->orderBy('p.seq', 'ASC')
            ->setParameter('subcategoryId', $subcategoryId);

        $products = $qb->getQuery()->getResult();

        return $products;
    }

    public function getSeqForNewProductInSubcategory(int $subcategoryId): int {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('max(p.seq) as maxseq')
            ->from('App:Product', 'p')
            ->where('p.subcategory = :subcategoryId')
            ->setParameter('subcategoryId', $subcategoryId);

        $maxSeq = (int)$qb->getQuery()->getResult()[0]['maxseq'];

        return $maxSeq + 1;
    }
}
