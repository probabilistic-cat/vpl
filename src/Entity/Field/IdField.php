<?php

declare(strict_types=1);

namespace App\Entity\Field;

use Doctrine\ORM\Mapping as ORM;

trait IdField
{
    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    public function getId(): int {
        return $this->id;
    }
}
