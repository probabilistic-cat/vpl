<?php

namespace App\Controller;

use App\Entity;
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
        $mainPage = $this->getDoctrine()->getRepository(Entity\MainPage::class)->find(Entity\MainPage::ID);
        $misc = $this->getDoctrine()->getRepository(Entity\Misc::class)->find(Entity\MainPage::ID);

        return $this->render("page/categories.html.twig", array(
            'categories' => $categories,
            'mainPage' => $mainPage,
            'misc' => $misc,
        ));
    }
}
