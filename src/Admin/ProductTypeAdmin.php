<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\ProductType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/** @extends AbstractAdmin<ProductType> */
class ProductTypeAdmin extends AbstractAdmin
{
    use CommonAdmin;

    protected function configureFormFields(FormMapper $form): void {
        $productType = $this->getSubject();

        $form
            ->add('text', TextareaType::class, ['label' => 'Тип'])
            ->add('imgFile', FileType::class, $this->getFormImageOptions(
                '<img src="/' . $productType->img
                . '" class="admin-product-property-preview" style="max-height: 100px; max-width: 100px;" />',
                'Изображение (на странице продукта)',
            ))
            ->add('seq', NumberType::class, ['label' => 'Последовательность'])
        ;
    }
}
