<?php

namespace AppBundle\Utils;

use AppBundle\Entity;

class DataSet
{
    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    public function __construct(\Doctrine\Bundle\DoctrineBundle\Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return array
     */
    public function getCategoriesWithSubcategories()
    {
        $data = array();

        $categories = $this->doctrine->getRepository(Entity\Category::class)->findAll();

        foreach ($categories as $category) {
            $subcategories = $this->doctrine->getRepository(Entity\Subcategory::class)->findByCategory($category);

            $data[$category->getId()] = array(
                'category' => $category,
                'subcategories' => $subcategories
            );
        }

        return $data;
    }
}
