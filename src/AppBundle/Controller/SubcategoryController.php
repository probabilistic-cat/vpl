<?php

namespace AppBundle\Controller;

use AppBundle\Entity;
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
        $subcategoryId = $request->get('id');
        $subcategory = $this->getDoctrine()->getRepository(Entity\Subcategory::class)->findOneById($subcategoryId);
        $mainPage = $this->getDoctrine()->getRepository(Entity\MainPage::class)->find(Entity\MainPage::ID);

        $manufacturerId = $request->get('manufacturer');
        if (isset($manufacturerId)) {
            $repo = $this->getDoctrine()->getManager()->getRepository(Entity\Product::class);
            $products = $repo->findByManufacturerId($manufacturerId);
        } else {
            $products = $subcategory->getProducts();
        }

        $manufacturers = $this->getManufacturersFromProducts($subcategory->getProducts());

        return $this->render("@App/page/subcategory.html.twig", array(
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
