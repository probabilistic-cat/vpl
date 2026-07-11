<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\PropertyItem;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PropertyItemAdmin extends AbstractAdmin
{
    use CommonAdmin;

    protected function configureFormFields(FormMapper $form): void {
        /** @var PropertyItem $propertyItem */
        $propertyItem = $this->getSubject();

        $form
            ->add('name', TextType::class, ['label' => 'Название', 'required' => false])
            ->add('imgFile', FileType::class, $this->getFormImageOptions(
                '<img src="/' . $propertyItem->img
                . '" class="admin-product-property-preview" style="max-height: 100px; max-width: 100px;" />',
                'Изображение',
                $propertyItem->isNew(),
            ))
            ->add('seq', TextType::class, ['label' => 'Последовательность'])
        ;
    }
}
