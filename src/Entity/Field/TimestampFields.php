<?php

declare(strict_types=1);

namespace App\Entity\Field;

use Doctrine\ORM\Mapping as ORM;

trait TimestampFields
{
    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }
}
