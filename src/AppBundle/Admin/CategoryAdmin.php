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
        $formMapper
            ->with('Категория', ['class' => 'col-md-9'])
                ->add('name', Type\TextType::class)
                ->add('description', Type\TextareaType::class)
            ->end()
            ->with('Изображение', ['class' => 'col-md-3'])
                ->add('img', Type\TextareaType::class)
            ->end()
            ->with('Цвет', ['class' => 'col-md-3'])
                ->add('color', Type\ColorType::class)
            ->end();
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
}
