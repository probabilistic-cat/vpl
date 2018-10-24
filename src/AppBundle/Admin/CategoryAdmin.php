<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type;

class CategoryAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        $container = $this->getConfigurationPool()->getContainer();
        $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/' . $object->getImg();
        $fileFieldOptions['help'] = '<img src="' . $fullPath . '" class="admin-category-preview" />';

        $formMapper
            ->with('Категория', ['class' => 'col-md-9'])
                ->add('name', Type\TextType::class)
                ->add('description', Type\TextareaType::class)
                ->add('color', Type\ColorType::class)
            ->end()
            ->with('Изображение', ['class' => 'col-md-3'])
                ->add('imgFile', Type\FileType::class, $fileFieldOptions)
            ->end()
            /*->with('Цвет', ['class' => 'col-md-3'])
                ->add('color', Type\ColorType::class)
            ->end()*/;
    }

    /*protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }*/

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name');
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
