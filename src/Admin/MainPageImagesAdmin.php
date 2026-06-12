<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MainPageImagesAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        $fileFieldOptions = [
            'required' => false,
            'label' => 'Изображение',
        ];

        if (!is_null($object)) {
            $fullPath = '/' . $object->getImg();
            $fileFieldOptions['help'] = '<img src="' . $fullPath . '" class="admin-firstline-preview" '
                . 'style="max-height: 300px; max-width: 500px;" />';
        }

        $formMapper
            ->add('imgFile', FileType::class, $fileFieldOptions)
            ->add('header', TextType::class, ['label' => 'Заголовок', 'required' => false])
            ->add('text', TextareaType::class, ['label' => 'Текст', 'required' => false])
            ->add('seq', NumberType::class, ['label' => 'Последовательность']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('seq', 'text', ['label' => 'Номер', 'header_class' => 'col-md-3'])
            ->add('header', 'text', ['label' => 'Заголовок', 'header_class' => 'col-md-9']);
    }

    public function toString($object)
    {
        return 'MainPageImage';
    }

    public function prePersist($object): void
    {
        $this->manageImgFileUpload($object);
    }

    public function preUpdate($object): void
    {
        $this->manageImgFileUpload($object);
    }

    private function manageImgFileUpload($object): void
    {
        if ($object->getImgFile()) {
            $object->refreshUpdated();
        }
    }
}
