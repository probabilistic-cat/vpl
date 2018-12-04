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
            ->add('name', Type\TextType::class)
            ->add('text', Type\TextareaType::class, array('required' => false))
            ->add('is_gallery', Type\CheckboxType::class, array('required' => false))
            ->add('seq', Type\TextType::class)
            ->add('productInfoMiddleGalleries', SonataCollectionType::class,
                array(
                    'by_reference' => false,
                    'required' => false,
                ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'seq',
                )
            )
            ->end();
    }
}
