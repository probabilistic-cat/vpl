<?php

declare(strict_types=1);

namespace App\Admin;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Manufacturer;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ManufacturerAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        $container = $this->getConfigurationPool()->getContainer();
        $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/' . $object->getImg();
        $fileFieldOptions = [
            'help' => '<img src="' . $fullPath
                . '" class="admin-manufacturer-preview" style="max-height: 300px; max-width: 300px;" />',
            'required' => false,
            'label' => 'Изображение'
        ];

        $formMapper
            ->with('Категория', ['class' => 'col-md-9'])
                ->add('name', TextType::class, ['label' => 'Название'])
            ->end()
            ->with('Изображение', ['class' => 'col-md-3'])
                ->add('imgFile', FileType::class, $fileFieldOptions)
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
        return $object instanceof Manufacturer
            ? $object->getName()
            : 'Manufacturer';
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
