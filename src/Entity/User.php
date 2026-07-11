<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
#[ORM\UniqueConstraint(name: 'iu__user__name', columns: ['name'])]
#[ORM\UniqueConstraint(name: 'iu__user__mail', columns: ['mail'])]
class User extends BaseEntity implements UserInterface, PasswordAuthenticatedUserInterface, \Stringable
{
    use IdField;
    use TimestampFields;

    private const bool ACTIVE_DEFAULT = false;
    private const string ROLES_DELIMETER = ',';

    #[ORM\Column(length: 100)]
    public string $name;

    #[ORM\Column(length: 60, options: ['fixed' => true])]
    public string $password;

    #[ORM\Column]
    public string $mail;

    #[ORM\Column]
    public string $role;

    #[ORM\Column(options: ['default' => self::ACTIVE_DEFAULT])]
    public bool $active = self::ACTIVE_DEFAULT;

    public function getPassword(): string {
        return $this->password;
    }

    /** @return string[] */
    public function getRoles(): array {
        return explode(self::ROLES_DELIMETER, $this->role);
    }

    public function getUsername(): string {
        return $this->name;
    }

    public function getUserIdentifier(): string {
        return $this->name;
    }

    #[\Deprecated]
    public function eraseCredentials(): void {}

    public function serialize(): string {
        return serialize([
            $this->id,
            $this->name,
            $this->password,
        ]);
    }

    public function unserialize($serialized) {
        return [
            $this->id,
            $this->name,
            $this->password,
        ] = unserialize($serialized);
    }

    public function __toString(): string {
        return $this->name ?? 'User';
    }
}
