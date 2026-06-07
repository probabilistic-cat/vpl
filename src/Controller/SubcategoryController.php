<?php

namespace App\Controller;

use App\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubcategoryController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $subcategoryId = (int)$request->get('id');
        $subcategory = $this->getDoctrine()->getRepository(Entity\Subcategory::class)->findOneById($subcategoryId);
        $mainPage = $this->getDoctrine()->getRepository(Entity\MainPage::class)->find(Entity\MainPage::ID);

        $manufacturerId = $request->get('manufacturer') !== null ? (int)$request->get('manufacturer') : null;
        $repo = $this->getDoctrine()->getManager()->getRepository(Entity\Product::class);
        $subcategoryProducts = $repo->findBySubcategory($subcategoryId);
        if (!is_null($manufacturerId)) {
            $products = $repo->findBySubcategoryManufacturer($subcategoryId, $manufacturerId);
        } else {
            $products = $subcategoryProducts;
        }

        $manufacturers = $this->getManufacturersFromProducts($subcategoryProducts);

        return $this->render("page/subcategory.html.twig", array(
            'subcategory' => $subcategory,
            'products' => $products,
            'manufacturers' => $manufacturers,
            'selectedManufacturerId' => $manufacturerId,
            'mainPage' => $mainPage,
        ));
    }

    private function getManufacturersFromProducts($products)
    {
        $manufacturersIds = array();
        foreach ($products as $product) {
            foreach ($product->getProductManufacturers() as $productManufacturer) {
                $manufacturersIds[] = $productManufacturer->getManufacturer()->getId();
            }
        }
        array_unique($manufacturersIds);
        $manufacturers = $this->getDoctrine()->getRepository(Entity\Manufacturer::class)->findBy(
            array('id' => $manufacturersIds), array('id' => 'ASC'));

        return $manufacturers;
    }
}
