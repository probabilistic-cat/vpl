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
        //$subcategory = $product->getSubcategory();

        $dataset = new Utils\DataSet($this->getDoctrine());
        $catsWithSubs = $dataset->getCategoriesWithSubcategories();

        return $this->render("@App/page/product.html.twig", array(
            'catsWithSubs' => $catsWithSubs,
            'product' => $product
        ));
    }
}
