<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MainPage;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\Subcategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubcategoryController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    public function index(Request $request): Response {
        $subcategoryId = (int)$request->get('id');
        $subcategory = $this->em->getRepository(Subcategory::class)->findOneById($subcategoryId);
        $mainPage = $this->em->getRepository(MainPage::class)->find(MainPage::ID);

        $manufacturerId = $request->get('manufacturer') !== null ? (int)$request->get('manufacturer') : null;
        $repo = $this->em->getRepository(Product::class);
        $subcategoryProducts = $repo->findBySubcategory($subcategoryId);
        if (!is_null($manufacturerId)) {
            $products = $repo->findBySubcategoryManufacturer($subcategoryId, $manufacturerId);
        } else {
            $products = $subcategoryProducts;
        }

        $manufacturers = $this->getManufacturersFromProducts($subcategoryProducts);

        return $this->render('page/subcategory.html.twig', [
            'subcategory' => $subcategory,
            'products' => $products,
            'manufacturers' => $manufacturers,
            'selectedManufacturerId' => $manufacturerId,
            'mainPage' => $mainPage,
        ]);
    }

    private function getManufacturersFromProducts($products) {
        $manufacturersIds = [];
        foreach ($products as $product) {
            foreach ($product->getProductManufacturers() as $productManufacturer) {
                $manufacturersIds[] = $productManufacturer->getManufacturer()->getId();
            }
        }
        $manufacturersIds = array_unique($manufacturersIds);
        $manufacturers = $this->em->getRepository(Manufacturer::class)->findBy(
            ['id' => $manufacturersIds], ['id' => 'ASC'],
        );

        return $manufacturers;
    }
}
