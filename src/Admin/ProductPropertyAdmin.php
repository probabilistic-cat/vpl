<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\CategoryProperty;
use App\Entity\ProductProperty;
use App\Entity\PropertySet;
use App\Repository\CategoryPropertyRepository;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductPropertyAdmin extends AbstractAdmin
{
    use CommonAdmin;

    protected function configureFormFields(FormMapper $form): void {
        /** @var ProductProperty $productProperty */
        $productProperty = $this->getSubject();

        $category = $productProperty->product->subcategory->category;
        $categoryPropertiesWithoutDescQBFn = static fn (CategoryPropertyRepository $repo): QueryBuilder =>
            $repo->getQBWithoutDesc($category)
        ;

        $form
            ->add('categoryProperty', EntityType::class, [
                'class' => CategoryProperty::class,
                'query_builder' => $categoryPropertiesWithoutDescQBFn,
                'choice_label' => 'property.name',
                'label' => 'Свойство',
            ])
            ->add('name', TextType::class, ['label' => 'Название'])
            ->add('propertySet', EntityType::class, [
                'class' => PropertySet::class,
                'choice_label' => 'name',
                'label' => 'Набор свойств',
                'required' => false,
                //'disabled' => true,
            ])
            ->add('imgFile', FileType::class, $this->getFormImageOptions(
                '<img src="/' . $productProperty->img
                . '" class="admin-product-property-preview" style="max-height: 100px; max-width: 100px;" />',
                'Изображение',
            ))
            ->add('seq', TextType::class, ['label' => 'Последовательность', 'required' => true])
        ;
    }
}
