<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Category;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType as SonataCollectionType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryAdmin extends AbstractAdmin
{
    use CommonAdmin;

    protected function configureFormFields(FormMapper $form): void {
        /** @var Category $category */
        $category = $this->getSubject();

        $form
            ->tab('Категория')
                ->with('Категория', ['class' => 'col-md-9'])
                    ->add('name', TextType::class, ['label' => 'Название'])
                    ->add('description', TextareaType::class, ['required' => false, 'label' => 'Описание'])
                    ->add('color', ColorType::class, ['label' => 'Цвет'])
                ->end()
                ->with('Изображение', ['class' => 'col-md-3'])
                    ->add('imgFile', FileType::class, $this->getFormImageOptions(
                        '<img src="/' . $category->img
                        . '" class="admin-category-preview" style="max-height: 300px; max-width: 300px;" />',
                        'Изображение',
                    ))
                ->end()
            ->end()
            ->tab('Свойства')
                ->with('Cвойства категории')
                    ->add('categoryProperties', SonataCollectionType::class, [
                        'by_reference' => false,
                        'required' => false,
                        'label' => 'Свойства категории',
                        'btn_add' => 'Добавить',
                    ], ['edit' => 'inline', 'inline' => 'table', 'sortable' => 'seq'])
                ->end()
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void {
        $filter->add('name');
    }

    protected function configureListFields(ListMapper $list): void {
        $list->addIdentifier('name', 'text', [
            'label' => 'Название',
            'header_class' => 'col-md-12',
            'route' => ['name' => 'edit'],
        ]);
    }

    #[\Override]
    public function toString(object $object): string {
        /** @var Category $object */
        return $object->name;
    }
}
