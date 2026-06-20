<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\MainPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{id:categoryId}', name: 'app_category', requirements: ['id' => '\d+'])]
    public function index(EntityManagerInterface $em, int $categoryId): Response {
        $category = $em->getRepository(Category::class)->find($categoryId);
        if ($category === null) {
            throw $this->createNotFoundException();
        }

        $mainPage = $em->getRepository(MainPage::class)->get();

        return $this->render('page/category.html.twig', [
            'category' => $category,
            'mainPage' => $mainPage,
        ]);
    }
}
