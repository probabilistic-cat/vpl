<?php

namespace App\Controller;

use App\Entity;
//use App\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $productId = $request->get('id');
        $product = $this->getDoctrine()->getRepository(Entity\Product::class)->findOneById($productId);
        $categories = $this->getDoctrine()->getRepository(Entity\Category::class)->findAll();

        // TODO get products ids without products
        $products = $product->getSubcategory()->getProducts()->toArray();
        $prodsIds = array_map(function(Entity\Product $product) { return $product->getId(); }, $products);

        $prodsCount = count($prodsIds);
        if ($prodsCount == 1) {
            $productIdNext = $productIdPrev = $prodsIds[0];
        } else {
            foreach ($prodsIds as $key => $prodId) {
                if ($prodId == $productId) {
                    if ($key == 0) {
                        $productIdNext = $prodsIds[$key + 1];
                        $productIdPrev = $prodsIds[$prodsCount - 1];
                    } else if ($key == ($prodsCount - 1)) {
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

        $mainPage = $this->getDoctrine()->getRepository(Entity\MainPage::class)->find(Entity\MainPage::ID);
        $misc = $this->getDoctrine()->getRepository(Entity\Misc::class)->find(Entity\MainPage::ID);

        return $this->render("page/product.html.twig", array(
            'categories' => $categories,
            'product' => $product,
            'productIdNext' => $productIdNext,
            'productIdPrev' => $productIdPrev,
            'mainPage' => $mainPage,
            'misc' => $misc,
        ));
    }
}
