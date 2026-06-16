<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity;
use App\Entity\CategoryProperty;
use App\Entity\PropertySet;
use App\Repository\CategoryPropertyRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductPropertyAdmin extends AbstractAdmin
{
    /** @var Entity\Category */
    private $category;

    protected function configureFormFields(FormMapper $formMapper): void {
        $productProperty = $this->getSubject();
        $product = $this->getRoot()->getSubject();
        $fileFieldOptions = [
            'required' => false,
            'label' => 'Изображение',
        ];

        $this->category = $product->getSubcategory()->getCategory();
        if (!is_null($productProperty)) {
            $fullPath = '/' . $productProperty->getImg();
            $fileFieldOptions = [
                'help' => '<img src="' . $fullPath . '" class="admin-product-property-preview" '
                . 'style="max-height: 100px; max-width: 100px;" />',
                'help_html' => true,
            ];
        }

        $formMapper
            ->add('categoryProperty', EntityType::class, [
                'class' => CategoryProperty::class,
                'query_builder' => fn (CategoryPropertyRepository $repo) => $repo->createCategoryQueryBuilder($this->category),
                'choice_label' => 'property.name',
                'label' => 'Свойство',
            ],
            )
            ->add('name', TextType::class, ['label' => 'Название'])
            ->add('propertySet', EntityType::class, [
                'class' => PropertySet::class,
                'choice_label' => 'name',
                'label' => 'Набор свойств',
                'required' => false,
                //'disabled' => true,
            ],
            )
            ->add('imgFile', FileType::class, $fileFieldOptions)
            ->add('seq', TextType::class, ['label' => 'Последовательность', 'required' => true]);
    }

    public function prePersist($object): void {
        $this->manageImgFileUpload($object);
    }

    public function preUpdate($object): void {
        $this->manageImgFileUpload($object);
    }

    private function manageImgFileUpload($object): void {
        if ($object->getImgFile()) {
            $object->refreshUpdated();
        }
    }
}
