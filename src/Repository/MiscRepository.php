<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Misc;
use Doctrine\ORM\EntityRepository;

/** @extends EntityRepository<Misc> */
class MiscRepository extends EntityRepository
{
    private const int ID = 1;

    public function get(): Misc {
        /** @var Misc */
        return $this->find(self::ID);
    }
}
