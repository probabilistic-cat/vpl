<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Entity\Category;
use App\Entity\CategoryProperty;
use App\Entity\MainPage;
use App\Entity\MainPageImages;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\ProductInfoBottom;
use App\Entity\ProductInfoMiddle;
use App\Entity\ProductInfoMiddleGallery;
use App\Entity\ProductManufacturer;
use App\Entity\ProductProperty;
use App\Entity\ProductType;
use App\Entity\Property;
use App\Entity\PropertyItem;
use App\Entity\PropertySet;
use App\Entity\Style;
use App\Entity\StyleImg;
use App\Entity\StyleInfoBottom;
use App\Entity\Subcategory;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class DBTestHelper
{
    public static function createCategory(EntityManagerInterface $em): Category {
        $category = new Category();
        $category->setName(TestHelper::getRandomString());
        $em->persist($category);
        $em->flush();

        return $category;
    }

    public static function createCategoryProperty(
        EntityManagerInterface $em,
        Category $category,
        Property $property,
        int $seq,
    ): CategoryProperty {
        $categoryProperty = new CategoryProperty();
        $categoryProperty->setCategory($category);
        $categoryProperty->setProperty($property);
        $categoryProperty->setSeq($seq);
        $em->persist($categoryProperty);
        $em->flush();

        return $categoryProperty;
    }

    public static function createMainPage(EntityManagerInterface $em): MainPage {
        $mainPage = new MainPage();
        $em->persist($mainPage);
        $em->flush();

        return $mainPage;
    }

    public static function createMainPageImages(EntityManagerInterface $em, int $seq): MainPageImages {
        $mainPageImages = new MainPageImages();
        $mainPageImages->setSeq($seq);
        $em->persist($mainPageImages);
        $em->flush();

        return $mainPageImages;
    }

    public static function createManufacturer(EntityManagerInterface $em): Manufacturer {
        $manufacturer = new Manufacturer();
        $manufacturer->setName(TestHelper::getRandomString());
        $em->persist($manufacturer);
        $em->flush();

        return $manufacturer;
    }

    public static function createProduct(EntityManagerInterface $em, Subcategory $subcategory, int $seq): Product {
        $product = new Product();
        $product->setSubcategory($subcategory);
        $product->setName(TestHelper::getRandomString());
        $product->setSeq($seq);
        $em->persist($product);
        $em->flush();

        return $product;
    }

    public static function createProductInfoBottom(
        EntityManagerInterface $em,
        Product $product,
        int $seq,
    ): ProductInfoBottom {
        $productInfoBottom = new ProductInfoBottom();
        $productInfoBottom->setProduct($product);
        $productInfoBottom->setName(TestHelper::getRandomString());
        $productInfoBottom->setSeq($seq);
        $em->persist($productInfoBottom);
        $em->flush();

        return $productInfoBottom;
    }

    public static function createProductInfoMiddle(
        EntityManagerInterface $em,
        Product $product,
        int $seq,
    ): ProductInfoMiddle {
        $productInfoMiddle = new ProductInfoMiddle();
        $productInfoMiddle->setProduct($product);
        $productInfoMiddle->setSeq($seq);
        $em->persist($productInfoMiddle);
        $em->flush();

        return $productInfoMiddle;
    }

    public static function createProductInfoMiddleGallery(
        EntityManagerInterface $em,
        ProductInfoMiddle $productInfoMiddle,
        int $seq,
    ): ProductInfoMiddleGallery {
        $productInfoMiddleGallery = new ProductInfoMiddleGallery();
        $productInfoMiddleGallery->setProductInfoMiddle($productInfoMiddle);
        $productInfoMiddleGallery->setImgFile(TestHelper::getImgFile());
        $productInfoMiddleGallery->setSeq($seq);
        $em->persist($productInfoMiddleGallery);
        $em->flush();

        return $productInfoMiddleGallery;
    }

    public static function createProductManufacturer(
        EntityManagerInterface $em,
        Product $product,
        Manufacturer $manufacturer,
        int $seq,
    ): ProductManufacturer {
        $productManufacturer = new ProductManufacturer();
        $productManufacturer->setProduct($product);
        $productManufacturer->setManufacturer($manufacturer);
        $productManufacturer->setSeq($seq);
        $em->persist($productManufacturer);
        $em->flush();

        return $productManufacturer;
    }

    public static function createProductProperty(
        EntityManagerInterface $em,
        Product $product,
        CategoryProperty $categoryProperty,
        int $seq,
    ): ProductProperty {
        $productProperty = new ProductProperty();
        $productProperty->setProduct($product);
        $productProperty->setCategoryProperty($categoryProperty);
        $productProperty->setSeq($seq);
        $em->persist($productProperty);
        $em->flush();

        return $productProperty;
    }

    public static function createProductType(
        EntityManagerInterface $em,
        Product $product,
        string $text,
        int $seq,
    ): ProductType {
        $productType = new ProductType();
        $productType->setProduct($product);
        $productType->setText($text);
        $productType->setSeq($seq);
        $em->persist($productType);
        $em->flush();

        return $productType;
    }

    public static function createProperty(EntityManagerInterface $em): Property {
        $property = new Property();
        $property->setName(TestHelper::getRandomString());
        $em->persist($property);
        $em->flush();

        return $property;
    }

    public static function createPropertyItem(EntityManagerInterface $em, int $seq): PropertyItem {
        $propertyItem = new PropertyItem();
        $propertyItem->setImgFile(TestHelper::getImgFile());
        $propertyItem->setSeq($seq);
        $em->persist($propertyItem);
        $em->flush();

        return $propertyItem;
    }

    public static function createPropertySet(EntityManagerInterface $em, Property $property): PropertySet {
        $propertySet = new PropertySet();
        $propertySet->setProperty($property);
        $propertySet->setName(TestHelper::getRandomString());
        $em->persist($propertySet);
        $em->flush();

        return $propertySet;
    }

    public static function createStyle(EntityManagerInterface $em, int $seq): Style {
        $style = new Style();
        $style->setName(TestHelper::getRandomString());
        $style->setSeq($seq);
        $em->persist($style);
        $em->flush();

        return $style;
    }

    public static function createStyleImg(EntityManagerInterface $em, Style $style, int $seq): StyleImg {
        $styleImg = new StyleImg();
        $styleImg->setStyle($style);
        $styleImg->setSeq($seq);
        $em->persist($styleImg);
        $em->flush();

        return $styleImg;
    }

    public static function createStyleInfoBottom(EntityManagerInterface $em, Style $style, int $seq): StyleInfoBottom {
        $styleInfoBottom = new StyleInfoBottom();
        $styleInfoBottom->setStyle($style);
        $styleInfoBottom->setName(TestHelper::getRandomString());
        $styleInfoBottom->setSeq($seq);
        $em->persist($styleInfoBottom);
        $em->flush();

        return $styleInfoBottom;
    }

    public static function createSubcategory(EntityManagerInterface $em, Category $category): Subcategory {
        $subcategory = new Subcategory();
        $subcategory->setCategory($category);
        $subcategory->setName(TestHelper::getRandomString());
        $em->persist($subcategory);
        $em->flush();

        return $subcategory;
    }

    public static function createUser(EntityManagerInterface $em): User {
        $user = new User();
        $user->setName(TestHelper::getRandomString());
        $user->setPassword(TestHelper::getRandomString());
        $user->setMail(TestHelper::getRandomString());
        $user->setRole(TestHelper::getRandomString());
        $em->persist($user);
        $em->flush();

        return $user;
    }

    public static function deleteCategory(EntityManagerInterface $em, int $categoryId): void {
        $category = $em->getRepository(Category::class)->find($categoryId);

        foreach ($category->getSubcategories() as $subcategory) {
            foreach ($subcategory->getProducts() as $product) {
                foreach ($product->getProductInfoMiddles() as $productInfoMiddle) {
                    $productInfoMiddle->getProductInfoMiddleGalleries()->clear();
                    $em->remove($productInfoMiddle);
                }
                $product->getProductInfoBottoms()->clear();
                $product->getProductManufacturers()->clear();
                $product->getProductProperties()->clear();
                $product->getProductTypes()->clear();
                $em->remove($product);
            }
            $em->remove($subcategory);
        }

        $category->getCategoryProperties()->clear();

        $em->remove($category);
        $em->flush();
    }

    public static function deleteMainPageImages(EntityManagerInterface $em, int $mainPageImagesId): void {
        $mainPageImages = $em->getRepository(MainPageImages::class)->find($mainPageImagesId);
        $em->remove($mainPageImages);
        $em->flush();
    }

    public static function deleteManufacturer(EntityManagerInterface $em, int $manufacturerId): void {
        $manufacturer = $em->getRepository(Manufacturer::class)->find($manufacturerId);
        $em->remove($manufacturer);
        $em->flush();
    }

    public static function deleteProperty(EntityManagerInterface $em, int $propertyId): void {
        $property = $em->getRepository(Property::class)->find($propertyId);
        foreach ($property->getPropertySets() as $propertySet) {
            $propertySet->getPropertyItems()->clear();
            $em->remove($propertySet);
        }
        $em->remove($property);
        $em->flush();
    }

    public static function deleteStyle(EntityManagerInterface $em, int $styleId): void {
        $style = $em->getRepository(Style::class)->find($styleId);
        $style->getStyleImgs()->clear();
        $style->getStyleInfoBottoms()->clear();
        $em->remove($style);
        $em->flush();
    }

    public static function deleteUser(EntityManagerInterface $em, int $userId): void {
        $user = $em->getRepository(User::class)->find($userId);
        $em->remove($user);
        $em->flush();
    }
}
