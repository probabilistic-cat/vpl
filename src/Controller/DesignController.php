<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\MainPage;
use App\Entity\Misc;
use App\Entity\Style;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DesignController extends AbstractController
{
    #[Route('/design', name: 'app_design')]
    public function index(EntityManagerInterface $em): Response {
        $styles = $em->getRepository(Style::class)->findAll();
        $selectedStyle = reset($styles);
        $categories = $em->getRepository(Category::class)->findAll();
        $mainPage = $em->getRepository(MainPage::class)->get();
        $misc = $em->getRepository(Misc::class)->get();

        return $this->render('page/design.html.twig', [
            'categories' => $categories,
            'styles' => $styles,
            'selectedStyle' => $selectedStyle,
            'mainPage' => $mainPage,
            'misc' => $misc,
        ]);
    }
}
