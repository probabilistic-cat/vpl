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

            $subcategoriesFormat = array();
            foreach ($subcategories as $subcategory) {
                $subcategoriesFormat[$subcategory->getId()] = array(
                    'data' => $subcategory
                );
            }

            $data[$category->getId()] = array(
                'data' => $category,
                'subcategories' => $subcategoriesFormat
            );
        }

        return $data;
    }

    /**
     * @param Entity\Category $category
     * @return array
     */
    public function getSubcategoriesWithProducts(Entity\Category $category)
    {
        $data = array();

        $subcategories = $this->doctrine->getRepository(Entity\Subcategory::class)->findByCategory($category);

        foreach ($subcategories as $subcategory) {
            $products = $this->doctrine->getRepository(Entity\Product::class)->findBySubcategory($subcategory);

            $productsFormat = array();
            foreach ($products as $product) {
                $productsFormat[$product->getId()] = array(
                    'data' => $product
                );
            }

            $data[$subcategory->getId()] = array(
                'data' => $subcategory,
                'products' => $productsFormat
            );
        }

        return $data;
    }
}
