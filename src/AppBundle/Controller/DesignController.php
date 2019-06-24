<?php

namespace AppBundle\Controller;

use AppBundle\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DesignController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $styles = $this->getDoctrine()->getRepository(Entity\Style::class)->findAll();
        /*$styleId = $request->get('id');
        $style = is_null($styleId)
            ? $styles[0]
            : $style = $this->getDoctrine()->getRepository(Entity\Style::class)->findOneById($styleId);*/
        $selectedStyle = $styles[0];

        $categories = $this->getDoctrine()->getRepository(Entity\Category::class)->findAll();
        $mainPage = $this->getDoctrine()->getRepository(Entity\MainPage::class)->find(Entity\MainPage::ID);

        return $this->render("@App/page/design.html.twig", array(
            'categories' => $categories,
            'styles' => $styles,
            //'style' => $style,
            'selectedStyle' => $selectedStyle,
            'mainPage' => $mainPage,
        ));
    }
}
