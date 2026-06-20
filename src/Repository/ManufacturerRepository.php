<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Manufacturer;
use Doctrine\ORM\EntityRepository;

class ManufacturerRepository extends EntityRepository
{
    /**
     * @param int[] $manufacturersIds
     * @return Manufacturer[]
     */
    public function findByIds(array $manufacturersIds): array {
        return $this->findBy(
            ['id' => $manufacturersIds], ['id' => 'ASC'],
        );
    }
}
