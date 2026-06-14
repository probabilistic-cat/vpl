<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Manufacturer;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductManufacturerAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
            ->add('manufacturer', EntityType::class, [
                'class' => Manufacturer::class,
                'choice_label' => 'name',
                'label' => 'Производитель',
            ],
            )
            ->add('seq', TextType::class, ['label' => 'Последовательность']);
    }
}
