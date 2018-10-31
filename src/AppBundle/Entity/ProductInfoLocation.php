<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductInfoLocation
 *
 * @ORM\Table(name="product_info_location")
 * @ORM\Entity
 */
class ProductInfoLocation
{
    const CODE_BOTTOM = 'bottom';
    const CODE_MIDDLE = 'middle';

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=32)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $code;

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
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return ProductInfoLocation
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modified.
     *
     * @param \DateTime|null $modified
     *
     * @return ProductInfoLocation
     */
    public function setModified($modified = null)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified.
     *
     * @return \DateTime|null
     */
    public function getModified()
    {
        return $this->modified;
    }
}
