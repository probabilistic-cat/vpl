<?php

namespace AppBundle\Entity;

/**
 * ProductInfoLocation
 */
class ProductInfoLocation
{
    const CODE_BOTTOM = 'bottom';
    const CODE_MIDDLE = 'middle';

    /**
     * @var string
     */
    private $code;

    /**
     * @var \DateTime
     */
    private $created = '2000-01-01 00:00:00';

    /**
     * @var \DateTime|null
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
