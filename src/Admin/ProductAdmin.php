<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Subcategory;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType as SonataCollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductAdmin extends AbstractAdmin
{
    use CommonAdmin;

    protected function configureFormFields(FormMapper $form): void {
        $root = $this->getRoot();

        if ($root instanceof self) {
            $this->setFormMapperProductPage($form);
        } elseif ($root instanceof SubcategoryAdmin) {
            $this->setFormMapperSubcategoryPage($form);
        }
    }

    private function setFormMapperProductPage(FormMapper $form): void {
        /** @var Product $product */
        $product = $this->getSubject();

        $form
            ->tab('Продукт')
                ->with('Подкатегория', ['class' => 'col-md-12'])
                    ->add('subcategory', EntityType::class, [
                        'class' => Subcategory::class,
                        'choice_label' => 'name',
                        'label' => 'Подкатегория',
                    ])
                ->end()
                ->with('Продукт', ['class' => 'col-md-9'])
                    ->add('name', TextType::class, ['label' => 'Название'])
                    ->add('description', TextareaType::class, ['label' => 'Описание (на странице подкатегории)'])
                    ->add('descriptionFull', TextareaType::class, [
                        'label' => 'Полное описание (на странице продукта)',
                    ])
                    ->add('seals', TextType::class, ['label' => 'Dichtungen', 'required' => false])
                    ->add('chambers', TextType::class, ['label' => 'Kammern', 'required' => false])
                    ->add('chambersName', TextType::class, ['label' => 'Название Kammern'])
                ->end()
                ->with('Изображение', ['class' => 'col-md-3'])
                    ->add('imgFile', FileType::class, $this->getFormImageOptions(
                        '<img src="/' . $product->img
                        . '" class="admin-product-preview" style="max-height: 300px; max-width: 300px;" />',
                        'Изображение (на странице подкатегории)',
                    ))
                ->end()
            ->end()
            ->tab('Типы')
                ->with('Типы продукта')
                    ->add('productTypes', SonataCollectionType::class, [
                        'by_reference' => false,
                        'required' => false,
                        'label' => 'Типы продукта',
                        'btn_add' => 'Добавить',
                    ], ['edit' => 'inline', 'inline' => 'table', 'sortable' => 'seq'])
                ->end()
            ->end()
            ->tab('Свойства')
                ->with('Свойства продукта')
                    ->add('productProperties', SonataCollectionType::class, [
                        'by_reference' => false,
                        'required' => false,
                        'label' => 'Свойства продукта',
                        'btn_add' => 'Добавить',
                    ], ['edit' => 'inline', 'inline' => 'table'])
                ->end()
            ->end()
            ->tab('Средний инфоблок')
                ->with('Средний инфоблок')
                    ->add('productInfoMiddles', SonataCollectionType::class, [
                        'by_reference' => false,
                        'required' => false,
                        'label' => 'Средний инфоблок',
                        'btn_add' => 'Добавить',
                    ], ['edit' => 'inline', 'inline' => 'table', 'sortable' => 'seq'])
                ->end()
            ->end()
            ->tab('Нижний инфоблок')
                ->with('Нижний инфоблок')
                    ->add('productInfoBottoms', SonataCollectionType::class, [
                        'by_reference' => false,
                        'required' => false,
                        'label' => 'Нижний инфоблок',
                        'btn_add' => 'Добавить',
                        'help' => '<span id="spanProductInfoBottoms"></span>',
                        'help_html' => true,
                    ], ['edit' => 'inline', 'inline' => 'table', 'sortable' => 'seq'])
                ->end()
            ->end()
            ->tab('Производители')
                ->with('Производители продукта')
                    ->add('productManufacturers', SonataCollectionType::class, [
                        'by_reference' => false,
                        'required' => false,
                        'label' => 'Производители продукта',
                        'btn_add' => 'Добавить',
                    ], ['edit' => 'inline', 'inline' => 'table', 'sortable' => 'seq'])
                ->end()
            ->end()
        ;
    }

    private function setFormMapperSubcategoryPage(FormMapper $form): void {
        $form
            ->add('name', TextType::class, ['label' => 'Название', 'attr' => ['readonly' => true]])
            ->add('seq', TextType::class, ['label' => 'Последовательность'])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void {
        $filter
            ->add('name')
            ->add('subcategory', null, [
                'field_options' => ['class' => Subcategory::class, 'choice_label' => 'name'],
                'field_type' => EntityType::class,
            ])
            ->add('subcategory.category', null, [
                'field_options' => ['class' => Category::class, 'choice_label' => 'name'],
                'field_type' => EntityType::class,
            ])
        ;
    }

    protected function configureListFields(ListMapper $list): void {
        $list
            ->add('subcategory.category.name', 'text', ['label' => 'Категория', 'header_class' => 'col-md-3'])
            ->add('subcategory.name', 'text', ['label' => 'Подкатегория', 'header_class' => 'col-md-3'])
            ->addIdentifier('name', 'text', [
                'label' => 'Название',
                'header_class' => 'col-md-6',
                'route' => ['name' => 'edit'],
            ])
        ;
    }

    #[\Override]
    public function toString(object $object): string {
        /** @var Product $object */
        return $object->name;
    }
}
