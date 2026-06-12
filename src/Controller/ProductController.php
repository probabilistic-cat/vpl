<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\MainPage;
use App\Entity\Misc;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    public function index(Request $request): Response
    {
        $productId = $request->get('id');
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneById($productId);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        // TODO get products ids without products
        $products = $product->getSubcategory()->getProducts()->toArray();
        $prodsIds = array_map(fn (Product $product) => $product->getId(), $products);

        $prodsCount = count($prodsIds);
        if ($prodsCount === 1) {
            $productIdNext = $productIdPrev = $prodsIds[0];
        } else {
            foreach ($prodsIds as $key => $prodId) {
                if ($prodId == $productId) {
                    if ($key == 0) {
                        $productIdNext = $prodsIds[$key + 1];
                        $productIdPrev = $prodsIds[$prodsCount - 1];
                    } elseif ($key == ($prodsCount - 1)) {
                        $productIdNext = $prodsIds[0];
                        $productIdPrev = $prodsIds[$key - 1];
                    } else {
                        $productIdNext = $prodsIds[$key + 1];
                        $productIdPrev = $prodsIds[$key - 1];
                    }
                    break;
                }
            }
        }

        $mainPage = $this->getDoctrine()->getRepository(MainPage::class)->find(MainPage::ID);
        $misc = $this->getDoctrine()->getRepository(Misc::class)->find(MainPage::ID);

        return $this->render('page/product.html.twig', [
            'categories' => $categories,
            'product' => $product,
            'productIdNext' => $productIdNext,
            'productIdPrev' => $productIdPrev,
            'mainPage' => $mainPage,
            'misc' => $misc,
        ]);
    }
}
