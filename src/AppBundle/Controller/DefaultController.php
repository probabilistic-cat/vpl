<?php

namespace AppBundle\Controller;

use AppBundle\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(Entity\Category::class)->findAll();
        $mainPage = $this->getDoctrine()->getRepository(Entity\MainPage::class)->find(Entity\MainPage::ID);
        $mainPageImages = $this->getDoctrine()->getRepository(Entity\MainPageImages::class)->findAll();
        $misc = $this->getDoctrine()->getRepository(Entity\Misc::class)->find(Entity\MainPage::ID);

        return $this->render("@App/page/index.html.twig", array(
            'categories' => $categories,
            'mainPage' => $mainPage,
            'mainPageImages' => $mainPageImages,
            'misc' => $misc,
        ));
    }
}
