<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\MainPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    public function index(Request $request): Response {
        $categoryId = $request->get('id');
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneById($categoryId);
        $mainPage = $this->getDoctrine()->getRepository(MainPage::class)->find(MainPage::ID);

        return $this->render('page/category.html.twig', [
            'category' => $category,
            'mainPage' => $mainPage,
        ]);
    }
}
