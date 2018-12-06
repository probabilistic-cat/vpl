<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class SubcategoryAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        $container = $this->getConfigurationPool()->getContainer();
        $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/' . $object->getImg();
        $fileFieldOptions['help'] = '<img src="' . $fullPath . '" class="admin-subcategory-preview" />';
        $fileFieldOptions['required'] = false;

        $formMapper
            ->with('Категория', ['class' => 'col-md-12'])
                ->add('category', EntityType::class, [
                        'class' => Entity\Category::class,
                        'choice_label' => 'name',
                    ]
                )
            ->end()
            ->with('Подкатегория', ['class' => 'col-md-9'])
                ->add('name', Type\TextType::class)
                ->add('description', Type\TextareaType::class, array('required' => false))
            ->end()
            ->with('Изображение', ['class' => 'col-md-3'])
                ->add('imgFile', Type\FileType::class, $fileFieldOptions)
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('category', null, array(), EntityType::class, [
                    'class' => Entity\Category::class,
                    'choice_label' => 'name',
                ]
            );
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('category.name')
            ->addIdentifier('name');
    }

    public function toString($object)
    {
        return $object instanceof Entity\Subcategory
            ? $object->getName()
            : 'Subcategory';
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
