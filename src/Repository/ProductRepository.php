<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    /** @return Product[] */
    public function findBySubcategoryManufacturer(int $subcategoryId, int $manufacturerId): array {
        $qb = $this->createQueryBuilder('p')
            ->innerjoin('p.productManufacturers', 'pm')
            ->where('p.subcategory = :subcategoryId AND pm.manufacturer = :manufacturerId')
            ->orderBy('p.seq', 'ASC')
            ->setParameter('subcategoryId', $subcategoryId)
            ->setParameter('manufacturerId', $manufacturerId)
        ;

        return $qb->getQuery()->getResult();
    }

    public function getSeqForNewProductInSubcategory(int $subcategoryId): int {
        $qb = $this->createQueryBuilder('p')
            ->select('max(p.seq) as maxseq')
            ->where('p.subcategory = :subcategoryId')
            ->setParameter('subcategoryId', $subcategoryId)
        ;

        $maxSeq = (int)$qb->getQuery()->getResult()[0]['maxseq'];

        return $maxSeq + 1;
    }
}
