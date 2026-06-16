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
    private string $name;

    #[ORM\Column(length: 60, options: ['fixed' => true])]
    private string $password;

    #[ORM\Column]
    private string $mail;

    #[ORM\Column]
    private string $role;

    #[ORM\Column(options: ['default' => false])]
    private bool $active = false;

    #[ORM\Column(options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    public function getId(): int {
        return $this->id;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setPassword(string $password): self {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setMail(string $mail): self {
        $this->mail = $mail;

        return $this;
    }

    public function getMail(): string {
        return $this->mail;
    }

    public function setRole(string $role): self {
        $this->role = $role;

        return $this;
    }

    public function getRole(): string {
        return $this->role;
    }

    /** @return string[] */
    public function getRoles(): array {
        return explode(self::ROLES_DELIMETER, $this->role);
    }

    public function setActive(bool $active): self {
        $this->active = $active;

        return $this;
    }

    public function getActive(): bool {
        return $this->active;
    }

    public function setCreated(\DateTime $created): self {
        $this->created = $created;

        return $this;
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function setModified(?\DateTime $modified = null): self {
        $this->modified = $modified;

        return $this;
    }

    public function getModified(): ?\DateTime {
        return $this->modified;
    }

    public function getSalt(): ?string {
        return null;
    }

    public function getUsername(): string {
        return $this->getName();
    }

    public function getUserIdentifier(): string {
        return $this->getName();
    }

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
