<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\ProductInfoMiddleGallery;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductInfoMiddleGalleryAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void {
        /** @var ProductInfoMiddleGallery $productInfoMiddleGallery */
        $productInfoMiddleGallery = $this->getSubject();
        $imgHtml = '<img src="/' . $productInfoMiddleGallery->img
            . '" class="admin-product-property-preview" style="max-height: 100px; max-width: 100px;" />'
        ;
        $imgOptions = ['help' => $imgHtml, 'help_html' => true, 'required' => false, 'label' => 'Изображение'];

        $form
            ->add('imgFile', FileType::class, $imgOptions)
            ->add('seq', TextType::class, ['label' => 'Посл.'])
        ;
    }

    #[\Override]
    public function toString(object $object): string {
        /** @var ProductInfoMiddleGallery $object */
        return 'ProductInfoMiddleGallery ' . $object->id;
    }
}
