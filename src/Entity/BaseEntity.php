<?php

declare(strict_types=1);

namespace App\Entity;

abstract class BaseEntity
{
    public function isNew(): bool {
        return !isset($this->id);
    }

    /** @return array<string|null> */
    public function getImagePaths(): array {
        return [];
    }
}
