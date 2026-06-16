<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\MainPage;
use App\Entity\Misc;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends AbstractController
{
    public function index(EntityManagerInterface $em): Response {
        $categories = $em->getRepository(Category::class)->findAll();
        $mainPage = $em->getRepository(MainPage::class)->find(MainPage::ID);
        $misc = $em->getRepository(Misc::class)->find(MainPage::ID);
        return $this->render('page/categories.html.twig', [
            'categories' => $categories,
            'mainPage' => $mainPage,
            'misc' => $misc,
        ]);
    }
}
