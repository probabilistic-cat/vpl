<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use AppBundle\Repository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\CollectionType as SonataCollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class StyleAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('Стиль')
                ->with('Стиль')
                    ->add('name', Type\TextType::class, ['label' => 'Название'])
                    ->add('seq', Type\TextType::class, ['label' => 'Последовательность'])
                ->end()
            ->end()
            ->tab('Изображения')
                ->with('Изображения')
                    ->add('styleImgs', SonataCollectionType::class,
                        array(
                            'by_reference' => false,
                            'required' => false,
                            'label' => 'Изображения',
                            'btn_add' => 'Добавить',
                        ),
                        array(
                            'edit' => 'inline',
                            'inline' => 'standard',
                        )
                    )
                ->end()
            ->end();
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', 'text', ['label' => 'Название', 'header_class' => 'col-md-12']);
    }
}
