<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class ProductInfoBottomAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', Type\TextType::class)
            ->add('text', Type\TextareaType::class, array('required' => false))
            ->add('seq', Type\TextType::class);
    }
}
