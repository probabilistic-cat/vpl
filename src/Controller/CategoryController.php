<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\MainPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    public function index(Request $request, EntityManagerInterface $em): Response {
        $categoryId = $request->get('id');
        $category = $em->getRepository(Category::class)->findOneById($categoryId);
        $mainPage = $em->getRepository(MainPage::class)->find(MainPage::ID);

        return $this->render('page/category.html.twig', [
            'category' => $category,
            'mainPage' => $mainPage,
        ]);
    }
}
