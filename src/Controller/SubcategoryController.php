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
use Symfony\Component\Routing\Attribute\Route;

class SubcategoryController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    #[Route('/subcategory/{id}', name: 'app_subcategory', requirements: ['id' => '\d+'])]
    public function index(Request $request): Response {
        $subcategoryId = (int)$request->attributes->get('id');
        $subcategory = $this->em->getRepository(Subcategory::class)->findOneById($subcategoryId);
        $mainPage = $this->em->getRepository(MainPage::class)->find(MainPage::ID);

        $manufacturerId = $request->attributes->get('manufacturer') !== null
            ? (int)$request->attributes->get('manufacturer')
            : null
        ;
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

    /** @return Manufacturer[] */
    private function getManufacturersFromProducts($products): array {
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
