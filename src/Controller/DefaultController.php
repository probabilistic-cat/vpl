<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\MainPage;
use App\Entity\MainPageImages;
use App\Entity\Misc;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $em): Response {
        $categories = $em->getRepository(Category::class)->findAll();
        $mainPage = $em->getRepository(MainPage::class)->find(MainPage::ID);
        $mainPageImages = $em->getRepository(MainPageImages::class)->findAll();
        $misc = $em->getRepository(Misc::class)->find(MainPage::ID);
        return $this->render('page/index.html.twig', [
            'categories' => $categories,
            'mainPage' => $mainPage,
            'mainPageImages' => $mainPageImages,
            'misc' => $misc,
        ]);
    }
}
