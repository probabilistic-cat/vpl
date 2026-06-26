<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Entity\Category;
use App\Entity\CategoryProperty;
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
        $category->name = TestHelper::getRandomString();
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
        $categoryProperty->category = $category;
        $categoryProperty->property = $property;
        $categoryProperty->seq = $seq;
        $em->persist($categoryProperty);
        $em->flush();

        return $categoryProperty;
    }

    public static function createMainPageImages(EntityManagerInterface $em, int $seq): MainPageImages {
        $mainPageImages = new MainPageImages();
        $mainPageImages->seq = $seq;
        $em->persist($mainPageImages);
        $em->flush();

        return $mainPageImages;
    }

    public static function createManufacturer(EntityManagerInterface $em): Manufacturer {
        $manufacturer = new Manufacturer();
        $manufacturer->name = TestHelper::getRandomString();
        $em->persist($manufacturer);
        $em->flush();

        return $manufacturer;
    }

    public static function createProduct(EntityManagerInterface $em, Subcategory $subcategory, int $seq): Product {
        $product = new Product();
        $product->subcategory = $subcategory;
        $product->name = TestHelper::getRandomString();
        $product->seq = $seq;
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
        $productInfoBottom->product = $product;
        $productInfoBottom->name = TestHelper::getRandomString();
        $productInfoBottom->seq = $seq;
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
        $productInfoMiddle->product = $product;
        $productInfoMiddle->seq = $seq;
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
        $productInfoMiddleGallery->productInfoMiddle = $productInfoMiddle;
        $productInfoMiddleGallery->imgFile = TestHelper::getImgFile();
        $productInfoMiddleGallery->seq = $seq;
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
        $productManufacturer->product = $product;
        $productManufacturer->manufacturer = $manufacturer;
        $productManufacturer->seq = $seq;
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
        $productProperty->product = $product;
        $productProperty->categoryProperty = $categoryProperty;
        $productProperty->seq = $seq;
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
        $productType->product = $product;
        $productType->text = $text;
        $productType->seq = $seq;
        $em->persist($productType);
        $em->flush();

        return $productType;
    }

    public static function createProperty(EntityManagerInterface $em): Property {
        $property = new Property();
        $property->name = TestHelper::getRandomString();
        $em->persist($property);
        $em->flush();

        return $property;
    }

    public static function createPropertyItem(
        EntityManagerInterface $em,
        PropertySet $propertySet,
        int $seq,
    ): PropertyItem {
        $propertyItem = new PropertyItem();
        $propertyItem->propertySet = $propertySet;
        $propertyItem->imgFile = TestHelper::getImgFile();
        $propertyItem->seq = $seq;
        $em->persist($propertyItem);
        $em->flush();

        return $propertyItem;
    }

    public static function createPropertySet(EntityManagerInterface $em, Property $property): PropertySet {
        $propertySet = new PropertySet();
        $propertySet->property = $property;
        $propertySet->name = TestHelper::getRandomString();
        $em->persist($propertySet);
        $em->flush();

        return $propertySet;
    }

    public static function createStyle(EntityManagerInterface $em, int $seq): Style {
        $style = new Style();
        $style->name = TestHelper::getRandomString();
        $style->seq = $seq;
        $em->persist($style);
        $em->flush();

        return $style;
    }

    public static function createStyleImg(EntityManagerInterface $em, Style $style, int $seq): StyleImg {
        $styleImg = new StyleImg();
        $styleImg->style = $style;
        $styleImg->seq = $seq;
        $em->persist($styleImg);
        $em->flush();

        return $styleImg;
    }

    public static function createStyleInfoBottom(EntityManagerInterface $em, Style $style, int $seq): StyleInfoBottom {
        $styleInfoBottom = new StyleInfoBottom();
        $styleInfoBottom->style = $style;
        $styleInfoBottom->name = TestHelper::getRandomString();
        $styleInfoBottom->seq = $seq;
        $em->persist($styleInfoBottom);
        $em->flush();

        return $styleInfoBottom;
    }

    public static function createSubcategory(EntityManagerInterface $em, Category $category): Subcategory {
        $subcategory = new Subcategory();
        $subcategory->category = $category;
        $subcategory->name = TestHelper::getRandomString();
        $em->persist($subcategory);
        $em->flush();

        return $subcategory;
    }

    public static function createUser(EntityManagerInterface $em): User {
        $user = new User();
        $user->name = TestHelper::getRandomString();
        $user->password = TestHelper::getRandomString();
        $user->mail = TestHelper::getRandomString();
        $user->role = TestHelper::getRandomString();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    public static function deleteCategory(EntityManagerInterface $em, int $categoryId): void {
        $category = $em->getRepository(Category::class)->find($categoryId);
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
        $em->remove($property);
        $em->flush();
    }

    public static function deleteStyle(EntityManagerInterface $em, int $styleId): void {
        $style = $em->getRepository(Style::class)->find($styleId);
        $em->remove($style);
        $em->flush();
    }

    public static function deleteUser(EntityManagerInterface $em, int $userId): void {
        $user = $em->getRepository(User::class)->find($userId);
        $em->remove($user);
        $em->flush();
    }
}
