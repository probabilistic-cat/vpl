<?php

namespace App\Admin;

use App\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class StyleInfoBottomAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', Type\TextType::class, ['label' => 'Название'])
            ->add('text', Type\TextareaType::class, ['required' => false, 'label' => 'Текст'])
            ->add('seq', Type\TextType::class, ['label' => 'Посл.']);
    }
}
