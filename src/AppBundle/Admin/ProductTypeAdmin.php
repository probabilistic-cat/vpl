<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class ProductTypeAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            /*->add('product', EntityType::class, [
                    'class' => Entity\Product::class,
                    'choice_label' => 'name',
                ]
            )*/
            ->add('text', Type\TextareaType::class)
            //->add('img', Type\TextareaType::class)
            ->add('seq', Type\NumberType::class);
    }
}
