<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Category;
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

class SubcategoryAdmin extends AbstractAdmin
{
    use CommonAdmin;

    protected function configureFormFields(FormMapper $form): void {
        /** @var Subcategory $subcategory */
        $subcategory = $this->getSubject();

        $form
            ->tab('Подкатегория')
                ->with('Подкатегория', ['class' => 'col-md-9'])
                    ->add('category', EntityType::class, [
                        'class' => Category::class,
                        'choice_label' => 'name',
                        'label' => 'Категория',
                    ])
                    ->add('name', TextType::class, ['label' => 'Название'])
                    ->add('description', TextareaType::class, ['required' => false, 'label' => 'Описание'])
                ->end()
                ->with('Изображение', ['class' => 'col-md-3'])
                    ->add('imgFile', FileType::class, $this->getFormImageOptions(
                        '<img src="/' . $subcategory->img
                        . '" class="admin-subcategory-preview" style="max-height: 300px; max-width: 300px;" />',
                        'Изображение',
                    ))
                ->end()
            ->end()
            ->tab('Продукты')
                ->with('Продукты подкатегории')
                    ->add('products', SonataCollectionType::class, [
                        'by_reference' => false,
                        'required' => false,
                        'label' => 'Продукты подкатегории',
                        'btn_add' => 'Добавить',
                    ], ['edit' => 'inline', 'inline' => 'table', 'sortable' => 'seq'])
                ->end()
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void {
        $filter
            ->add('name')
            ->add('category', null, [
                'field_options' => ['class' => Category::class, 'choice_label' => 'name'],
                'field_type' => EntityType::class,
            ])
        ;
    }

    protected function configureListFields(ListMapper $list): void {
        $list
            ->add('category.name', 'text', ['label' => 'Категория', 'header_class' => 'col-md-3'])
            ->addIdentifier('name', 'text', [
                'label' => 'Название',
                'header_class' => 'col-md-9',
                'route' => ['name' => 'edit'],
            ])
        ;
    }

    #[\Override]
    public function toString(object $object): string {
        /** @var Subcategory $object */
        return $object->name;
    }
}
