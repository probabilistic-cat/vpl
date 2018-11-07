<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class ProductInfoAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('productInfoLocation', EntityType::class, [
                    'class' => Entity\ProductInfoLocation::class,
                    'choice_label' => 'code',
                ]
            )
            ->add('name', Type\TextType::class)
            ->add('text', Type\TextareaType::class, array('required' => false))
            ->add('is_gallery', Type\CheckboxType::class)
            ->add('seq', Type\TextType::class);
    }
}
