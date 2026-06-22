<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
#[ORM\UniqueConstraint(name: 'iu__user__name', columns: ['name'])]
#[ORM\UniqueConstraint(name: 'iu__user__mail', columns: ['mail'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Stringable
{
    private const string ROLES_DELIMETER = ',';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(length: 100)]
    public string $name;

    #[ORM\Column(length: 60, options: ['fixed' => true])]
    public string $password;

    #[ORM\Column]
    public string $mail;

    #[ORM\Column]
    public string $role;

    #[ORM\Column(options: ['default' => false])]
    public bool $active = false;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    public function getId(): int {
        return $this->id;
    }

    public function getPassword(): string {
        return $this->password;
    }

    /** @return string[] */
    public function getRoles(): array {
        return explode(self::ROLES_DELIMETER, $this->role);
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
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
