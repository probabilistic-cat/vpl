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
        $subcategory = $product->getSubcategory();
        $productTypes = $this->getDoctrine()->getRepository(Entity\ProductType::class)->findByProduct($product);

        $dataset = new Utils\DataSet($this->getDoctrine());
        $catsWithSubs = $dataset->getCategoriesWithSubcategories();
        $productInfo = $dataset->getProductInfoByProduct($product);

        // TODO get products ids without products
        $subcatWithProds = $dataset->getSubcategoryWithProductsByProduct($product);
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
            'subcategory' => $subcategory,
            'product' => $product,
            'productInfo' => $productInfo,
            'productTypes' => $productTypes,
            'productIdNext' => $productIdNext,
            'productIdPrev' => $productIdPrev,
        ));
    }
}
