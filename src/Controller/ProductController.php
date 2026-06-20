<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\MainPage;
use App\Entity\Misc;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id:productId}', name: 'app_product', requirements: ['id' => '\d+'])]
    public function index(EntityManagerInterface $em, int $productId): Response {
        $product = $em->getRepository(Product::class)->find($productId);
        if ($product === null) {
            throw $this->createNotFoundException();
        }

        $products = $product->getSubcategory()->getProducts()->toArray();
        $productsIds = array_map(static fn (Product $product): int => $product->getId(), $products);
        $productsCount = count($productsIds);
        $productKey = array_search($productId, $productsIds, true);
        $nextProductKey = ($productKey + 1) % $productsCount;
        $nextProductId = $productsIds[$nextProductKey];
        $prevProductKey = ($productKey - 1 + $productsCount) % $productsCount;
        $prevProductId = $productsIds[$prevProductKey];

        $categories = $em->getRepository(Category::class)->findAll();
        $mainPage = $em->getRepository(MainPage::class)->get();
        $misc = $em->getRepository(Misc::class)->get();

        return $this->render('page/product.html.twig', [
            'categories' => $categories,
            'product' => $product,
            'nextProductId' => $nextProductId,
            'prevProductId' => $prevProductId,
            'mainPage' => $mainPage,
            'misc' => $misc,
        ]);
    }
}
