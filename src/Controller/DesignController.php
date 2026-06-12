<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\MainPage;
use App\Entity\Misc;
use App\Entity\Style;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DesignController extends AbstractController
{
    public function index(): Response
    {
        $styles = $this->getDoctrine()->getRepository(Style::class)->findAll();
        $selectedStyle = $styles[0];
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $mainPage = $this->getDoctrine()->getRepository(MainPage::class)->find(MainPage::ID);
        $misc = $this->getDoctrine()->getRepository(Misc::class)->find(MainPage::ID);
        return $this->render('page/design.html.twig', [
            'categories' => $categories,
            'styles' => $styles,
            'selectedStyle' => $selectedStyle,
            'mainPage' => $mainPage,
            'misc' => $misc,
        ]);
    }
}
