<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class CategoryPropertyAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('property', EntityType::class, [
                    'class' => Entity\Property::class,
                    'choice_label' => 'name',
                    'label' => 'Свойство'
                ]
            )
            ->add('layer', Type\TextType::class, ['label' => 'Слой (0 - нет наложения; 1 - нижний слой и т.д.). '
                . 'У Beschreibung всегда должен быть 0.'])
            ->add('seq', Type\TextType::class, ['label' => 'Последовательность']);
    }
}
