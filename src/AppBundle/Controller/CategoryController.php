<?php

namespace AppBundle\Controller;

use AppBundle\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $categoryId = $request->get('id');

        $dataset = new Utils\DataSet($this->getDoctrine());
        $catsWithSubs = $dataset->getCategoriesWithSubcategories();
        $catWithSubs = $catsWithSubs[$categoryId];

        return $this->render("@App/page/category.html.twig", array(
            'catWithSubs' => $catWithSubs
        ));
    }
}
