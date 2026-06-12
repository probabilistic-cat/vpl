<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\MainPage;
use App\Entity\MainPageImages;
use App\Entity\Misc;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function index(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $mainPage = $this->getDoctrine()->getRepository(MainPage::class)->find(MainPage::ID);
        $mainPageImages = $this->getDoctrine()->getRepository(MainPageImages::class)->findAll();
        $misc = $this->getDoctrine()->getRepository(Misc::class)->find(MainPage::ID);
        return $this->render('page/index.html.twig', [
            'categories' => $categories,
            'mainPage' => $mainPage,
            'mainPageImages' => $mainPageImages,
            'misc' => $misc,
        ]);
    }
}
