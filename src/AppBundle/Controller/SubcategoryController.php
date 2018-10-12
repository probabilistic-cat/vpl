<?php

namespace AppBundle\Controller;

use AppBundle\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubcategoryController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $subcategoryId = $request->get('id');
        $subcategory = $this->getDoctrine()->getRepository(Entity\Subcategory::class)->findOneById($subcategoryId);
        $products = $this->getDoctrine()->getRepository(Entity\Product::class)->findBySubcategory($subcategory);

        return $this->render("@App/page/subcategory.html.twig", array(
            'subcategory' => $subcategory,
            'products' => $products
        ));
    }
}
