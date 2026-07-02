<?php

declare(strict_types=1);

namespace App\Entity\Common;

use Doctrine\ORM\Mapping as ORM;

trait TimestampFields
{
    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private(set) \DateTime $created;

    #[ORM\Column(nullable: true)]
    private(set) ?\DateTime $modified = null;

    private function modifiedNow(): void {
        $this->modified = new \DateTime();
    }
}
