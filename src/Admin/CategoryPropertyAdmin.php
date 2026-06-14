<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Property;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryPropertyAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
            ->add('property', EntityType::class, [
                'class' => Property::class,
                'choice_label' => 'name',
                'label' => 'Свойство',
            ],
            )
            ->add('layer', TextType::class, ['label' => 'Слой (0 - нет наложения; 1 - нижний слой и т.д.). '
            . 'У Beschreibung всегда должен быть 0.', ])
            ->add('seq', TextType::class, ['label' => 'Последовательность']);
    }
}
