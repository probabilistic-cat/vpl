<?php

namespace App\Admin;

use App\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType as SonataCollectionType;
use Symfony\Component\Form\Extension\Core\Type;

class CategoryAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        $container = $this->getConfigurationPool()->getContainer();
        $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/' . $object->getImg();
        $fileFieldOptions = [
            'help' => '<img src="' . $fullPath
                . '" class="admin-category-preview" style="max-height: 300px; max-width: 300px;" />',
            'required' => false,
            'label' => 'Изображение'
        ];

        $formMapper
            ->tab('Категория')
                ->with('Категория', ['class' => 'col-md-9'])
                    ->add('name', Type\TextType::class, ['label' => 'Название'])
                    ->add('description', Type\TextareaType::class, ['required' => false, 'label' => 'Описание'])
                    ->add('color', Type\ColorType::class, ['label' => 'Цвет'])
                ->end()
                ->with('Изображение', ['class' => 'col-md-3'])
                    ->add('imgFile', Type\FileType::class, $fileFieldOptions)
                ->end()
            ->end()
            ->tab('Свойства')
                ->with('Cвойства категории')
                    ->add('categoryProperties', SonataCollectionType::class,
                        array(
                            'by_reference' => false,
                            'required' => false,
                            'label' => 'Свойства категории',
                            'btn_add' => 'Добавить',
                        ),
                        array(
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable' => 'seq',
                        )
                    )
                ->end()
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', 'text', ['label' => 'Название', 'header_class' => 'col-md-12']);
    }

    public function toString($object)
    {
        return $object instanceof Entity\Category
            ? $object->getName()
            : 'Category';
    }

    public function prePersist($object)
    {
        $this->manageImgFileUpload($object);
    }

    public function preUpdate($object)
    {
        $this->manageImgFileUpload($object);
    }

    private function manageImgFileUpload($object)
    {
        if ($object->getImgFile()) {
            $object->refreshUpdated();
        }
    }
}
