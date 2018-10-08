<?php

namespace AppBundle\Controller;

use AppBundle\Entity;
use AppBundle\Utils;
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

        $dataset = new Utils\DataSet($this->getDoctrine());
        $catsWithSubs = $dataset->getCategoriesWithSubcategories();
        $subcatWithProds = $dataset->getSubcategoryWithProductsByProduct($product);
        $productInfo = $dataset->getProductInfoByProduct($product);

        $prodsIds = array_keys($subcatWithProds['products']);
        $prodsCount = count($prodsIds);
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


        return $this->render("@App/page/product.html.twig", array(
            'catsWithSubs' => $catsWithSubs,
            'subcatWithProds' => $subcatWithProds,
            'productInfo' => $productInfo,
            'productId' => $productId,
            'productIdNext' => $productIdNext,
            'productIdPrev' => $productIdPrev,
        ));
    }
}
