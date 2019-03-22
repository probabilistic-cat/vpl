<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class MainPageImagesAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        $fileFieldOptions = [
            'required' => false,
            'label' => 'Изображение'
        ];

        if (!is_null($object)) {
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/' . $object->getImg();
            $fileFieldOptions['help'] = '<img src="' . $fullPath . '" class="admin-firstline-preview" '
                . 'style="max-height: 300px; max-width: 500px;" />';
        }

        $formMapper
            ->add('imgFile', Type\FileType::class, $fileFieldOptions)
            ->add('header', Type\TextType::class, ['label' => 'Заголовок', 'required' => false])
            ->add('text', Type\TextareaType::class, ['label' => 'Текст', 'required' => false])
            ->add('seq', Type\NumberType::class, ['label' => 'Последовательность']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('seq', 'text', ['label' => 'Номер', 'header_class' => 'col-md-3'])
            ->add('header', 'text', ['label' => 'Заголовк', 'header_class' => 'col-md-9']);
    }

    public function toString($object)
    {
        return 'MainPageImage';
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
