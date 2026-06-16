<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findAllOrderedByName() {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function findBySubcategoryManufacturer(int $subcategoryId, int $manufacturerId) {
        $qb = $this->createQueryBuilder('p')
            ->innerjoin('p.productManufacturers', 'pm')
            ->where('p.subcategory = :subcategoryId AND pm.manufacturer = :manufacturerId')
            ->orderBy('p.seq', 'ASC')
            ->setParameter('subcategoryId', $subcategoryId)
            ->setParameter('manufacturerId', $manufacturerId);

        return $qb->getQuery()->getResult();
    }

    public function findBySubcategory(int $subcategoryId) {
        $qb = $this->createQueryBuilder('p')
            ->where('p.subcategory = :subcategoryId')
            ->orderBy('p.seq', 'ASC')
            ->setParameter('subcategoryId', $subcategoryId);

        return $qb->getQuery()->getResult();
    }

    public function getSeqForNewProductInSubcategory(int $subcategoryId): int {
        $qb = $this->createQueryBuilder('p')
            ->select('max(p.seq) as maxseq')
            ->where('p.subcategory = :subcategoryId')
            ->setParameter('subcategoryId', $subcategoryId);

        $maxSeq = (int)$qb->getQuery()->getResult()[0]['maxseq'];

        return $maxSeq + 1;
    }
}
