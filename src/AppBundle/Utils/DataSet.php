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
    /*public function getCategoriesWithSubcategories()
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
    }*/

    /**
     * @param Entity\Category $category
     * @return array
     */
    /*public function getSubcategoriesWithProducts(Entity\Category $category)
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
    }*/

    /**
     * @param Entity\Subcategory $subcategory
     * @return array
     */
    /*public function getSubcategoryWithProducts(Entity\Subcategory $subcategory)
    {
        $data = array();

        $products = $this->doctrine->getRepository(Entity\Product::class)->findBySubcategory($subcategory);

        $productsFormat = array();
        foreach ($products as $product) {
            $productsFormat[$product->getId()] = array(
                'data' => $product
            );
        }

        $data = array(
            'data' => $subcategory,
            'products' => $productsFormat
        );

        return $data;
    }*/

    /**
     * @param Entity\Product $product
     * @return array
     */
    /*public function getSubcategoryWithProductsByProduct(Entity\Product $product)
    {
        $subcategory = $product->getSubcategory();

        return $this->getSubcategoryWithProducts($subcategory);
    }*/


    /**
     * @param Entity\Subcategory $subcategory
     * @return array
     */
    /*public function getProductsInfoBySubcategory(Entity\Subcategory $subcategory)
    {
        $result = array();

        $products = $this->doctrine->getRepository(Entity\Product::class)->findBySubcategory($subcategory);

        foreach ($products as $product) {
            $productData = array();

            $productInfos = $this->doctrine->getRepository(Entity\ProductInfo::class)->findByProduct($product);
            foreach ($productInfos as $productInfo) {
                $productInfoFormat = array(
                    'data' => $productInfo,
                    'galleries' => array()
                );

                if ($productInfo->isGallery()) {
                    $productInfoGalleries =
                        $this->doctrine->getRepository(Entity\ProductInfoGallery::class)->findByProductInfo($productInfo);
                    $productInfoFormat['galleries'] = $productInfoGalleries;
                }

                $productInfoLocation = $productInfo->getProductInfoLocationCode()->getCode();
                $productData[$productInfoLocation][] = $productInfoFormat;
            }

            if (!empty($productData)) {
                $result[$product->getId()] = $productData;
            }
        }

        return $result;
    }*/


    /**
     * @param Entity\Product $product
     * @return array
     */
    public function getProductInfoByProduct(Entity\Product $product)
    {
        $result = array(
            Entity\ProductInfoLocation::CODE_MIDDLE => array(),
            Entity\ProductInfoLocation::CODE_BOTTOM => array(),
        );

        $productInfos = $this->doctrine->getRepository(Entity\ProductInfo::class)->findByProduct($product);

        foreach ($productInfos as $productInfo) {
            $productInfoFormat = array(
                'data' => $productInfo,
                'galleries' => array()
            );

            if ($productInfo->isGallery()) {
                $productInfoGalleries =
                    $this->doctrine->getRepository(Entity\ProductInfoGallery::class)->findByProductInfo($productInfo);
                $productInfoFormat['galleries'] = $productInfoGalleries;
            }

            $productInfoLocation = $productInfo->getProductInfoLocation()->getCode();

            if (!empty($productInfoFormat)) {
                $result[$productInfoLocation][] = $productInfoFormat;
            }
        }

        return $result;
    }

    /**
     *
     * @param Entity\CategoryProperty[] $categoryProperties
     * @return array
     */
    /*public function getProductPropertiesByCategoryProperties(array $categoryProperties)
    {
        $result = array();

        foreach ($categoryProperties as $categoryProperty) {
            $productProperties = $this->doctrine->getRepository(Entity\ProductProperty::class)
                ->findByCategoryProperty($categoryProperty);

            $result[] = $productProperties;
        }

        return $result;
    }*/
}
