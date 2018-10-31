<?php

namespace AppBundle\Controller;

use AppBundle\Entity;
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
        $category = $this->getDoctrine()->getRepository(Entity\Category::class)->findOneById($categoryId);

        return $this->render("@App/page/category.html.twig", array(
            'category' => $category
        ));
    }
}
