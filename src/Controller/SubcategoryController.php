<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MainPage;
use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\Subcategory;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class SubcategoryController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    #[Route('/subcategory/{id:subcategoryId}', name: 'app_subcategory', requirements: ['id' => '\d+'])]
    public function index(
        int $subcategoryId,
        #[MapQueryParameter(name: 'manufacturer')] ?int $manufacturerId = null,
    ): Response {
        $subcategory = $this->em->getRepository(Subcategory::class)->find($subcategoryId);
        if ($subcategory === null) {
            throw $this->createNotFoundException();
        }

        if ($manufacturerId !== null) {
            $manufacturer = $this->em->getRepository(Manufacturer::class)->find($manufacturerId);
            if ($manufacturer === null) {
                throw $this->createNotFoundException();
            }
        }

        $subcategoryProducts = $subcategory->getProducts();
        $products = ($manufacturerId !== null)
            ? $this->em->getRepository(Product::class)->findBySubcategoryManufacturer($subcategoryId, $manufacturerId)
            : $subcategoryProducts
        ;

        $manufacturers = $this->getManufacturersFromProducts($subcategoryProducts);
        $mainPage = $this->em->getRepository(MainPage::class)->get();

        return $this->render('page/subcategory.html.twig', [
            'subcategory' => $subcategory,
            'products' => $products,
            'manufacturers' => $manufacturers,
            'selectedManufacturerId' => $manufacturerId,
            'mainPage' => $mainPage,
        ]);
    }

    /**
     * @param Collection<Product> $products
     * @return Manufacturer[]
     */
    private function getManufacturersFromProducts(Collection $products): array {
        $byManufacturersIds = [];
        foreach ($products as $product) {
            foreach ($product->getProductManufacturers() as $productManufacturer) {
                $manufacturerId = $productManufacturer->manufacturer->getId();
                $byManufacturersIds[$manufacturerId] = null;
            }
        }

        return $this->em->getRepository(Manufacturer::class)->findByIds(array_keys($byManufacturersIds));
    }
}
