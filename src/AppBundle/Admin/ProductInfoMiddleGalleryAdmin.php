<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class ProductInfoMiddleGalleryAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('img', Type\TextType::class)
            ->add('seq', Type\TextType::class);
    }
}
