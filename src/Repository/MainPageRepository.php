<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MainPage;
use Doctrine\ORM\EntityRepository;

/** @extends EntityRepository<MainPage> */
class MainPageRepository extends EntityRepository
{
    private const int ID = 1;

    public function get(): MainPage {
        /** @var MainPage */
        return $this->find(self::ID);
    }
}
