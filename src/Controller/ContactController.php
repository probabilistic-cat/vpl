<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use App\Entity\MainPage;
use App\Entity\Misc;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    public function index(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $mainPage = $this->getDoctrine()->getRepository(MainPage::class)->find(MainPage::ID);
        $misc = $this->getDoctrine()->getRepository(Misc::class)->find(MainPage::ID);
        return $this->render("page/contact.html.twig", [
            'categories' => $categories,
            'mainPage' => $mainPage,
            'misc' => $misc,
        ]);
    }
}
