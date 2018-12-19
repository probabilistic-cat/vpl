<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\CollectionType as SonataCollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class ProductInfoMiddleAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', Type\TextType::class, ['label' => 'Название'])
            ->add('text', Type\TextareaType::class, ['required' => false, 'label' => 'Текст'])
            ->add('is_gallery', Type\CheckboxType::class, ['required' => false, 'label' => 'Галерея'])
            ->add('productInfoMiddleGalleries', SonataCollectionType::class,
                array(
                    'by_reference' => false,
                    'required' => false,
                    'label' => 'Изображения галереи',
                    'btn_add' => 'Добавить',
                ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'seq',
                )
            )
            ->add('seq', Type\TextType::class, ['label' => 'Посл.'])
            ->end();
    }
}
