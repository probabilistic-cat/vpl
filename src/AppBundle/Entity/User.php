<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="`user`")
 * @ORM\Entity
 */
class User implements \Symfony\Component\Security\Core\User\UserInterface
{
    const ROLES_DELIMETER = ',';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=60, nullable=false, options={"fixed"=true})
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=false)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=false)
     */
    private $role;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false, options={"default"="2000-01-01 00:00:00"})
     */
    private $created;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    private $modified;



    /**
     * Get id.
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set password.
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set mail.
     * @param string $mail
     * @return User
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail.
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set role.
     * @param string $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get roles.
     * @return string
     */
    public function getRoles()
    {
        return explode(self::ROLES_DELIMETER, $this->role);
    }

    /**
     * Set active.
     * @param bool $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set created.
     * @param \DateTime $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modified.
     * @param \DateTime|null $modified
     * @return User
     */
    public function setModified($modified = null)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified.
     * @return \DateTime|null
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Get salt
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Get username
     * @return string
     */
    public function getUsername()
    {
        return $this->getName();
    }

    /**
     * Erase credentials
     */
    public function eraseCredentials() {}

    /**
     * Serialize
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->name,
            $this->password,
        ));
    }

    /**
     * Unserialize
     * @param mixed $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->name,
            $this->password,
        ) = unserialize($serialized);
    }

    public function __toString()
    {
        return get_class($this);
    }
}
