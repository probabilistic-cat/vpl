<?php

namespace AppBundle\Controller;

use AppBundle\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(Entity\Category::class)->findAll();

        return $this->render("@App/page/categories.html.twig", array(
            'categories' => $categories
        ));
    }
}
