<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MainPage;
use Doctrine\ORM\EntityRepository;

class MainPageRepository extends EntityRepository
{
    private const int ID = 1;

    public function get(): MainPage {
        return $this->find(self::ID);
    }
}
