<?php

namespace App\Admin;

use App\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class ProductManufacturerAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('manufacturer', EntityType::class, [
                    'class' => Entity\Manufacturer::class,
                    'choice_label' => 'name',
                    'label' => 'Производитель'
                ]
            )
            ->add('seq', Type\TextType::class, ['label' => 'Последовательность']);
    }
}
