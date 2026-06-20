<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductInfoBottomAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void {
        $form
            ->add('name', TextType::class, ['label' => 'Название'])
            ->add('text', TextareaType::class, ['required' => false, 'label' => 'Текст'])
            ->add('seq', TextType::class, ['label' => 'Посл.'])
        ;
    }
}
