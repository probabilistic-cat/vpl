<?php

namespace AppBundle\Controller;

use AppBundle\Entity;
use AppBundle\Utils;
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
        $category = $subcategory->getCategory();

        $dataset = new Utils\DataSet($this->getDoctrine());
        $subcatsWithProds = $dataset->getSubcategoriesWithProducts($category);
        $subcatWithProds = $subcatsWithProds[$subcategoryId];

        return $this->render("@App/page/subcategory.html.twig", array(
            'subcatWithProds' => $subcatWithProds,
            'category' => $category
        ));
    }
}
