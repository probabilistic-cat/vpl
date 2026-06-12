<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Manufacturer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductManufacturerAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('manufacturer', EntityType::class, [
                    'class' => Manufacturer::class,
                    'choice_label' => 'name',
                    'label' => 'Производитель'
                ]
            )
            ->add('seq', TextType::class, ['label' => 'Последовательность']);
    }
}
