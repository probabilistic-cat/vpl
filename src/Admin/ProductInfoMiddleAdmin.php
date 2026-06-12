<?php

declare(strict_types=1);

namespace App\Admin;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType as SonataCollectionType;

class ProductInfoMiddleAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', TextType::class, ['label' => 'Название'])
            ->add('text', TextareaType::class, ['required' => false, 'label' => 'Текст'])
            ->add('is_gallery', CheckboxType::class, ['required' => false, 'label' => 'Галерея'])
            ->add('productInfoMiddleGalleries', SonataCollectionType::class,
                [
                    'by_reference' => false,
                    'required' => false,
                    'label' => 'Изображения галереи',
                    'btn_add' => 'Добавить',
                ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'seq',
                ]
            )
            ->add('seq', TextType::class, ['label' => 'Посл.'])
            ->end();
    }
}
