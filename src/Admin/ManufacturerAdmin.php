<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Manufacturer;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ManufacturerAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $object = $this->getSubject();
        $fullPath = '/' . $object->getImg();
        $fileFieldOptions = [
            'help' => '<img src="' . $fullPath
                . '" class="admin-manufacturer-preview" style="max-height: 300px; max-width: 300px;" />',
            'help_html' => true,
            'required' => false,
            'label' => 'Изображение',
        ];

        $formMapper
            ->with('Категория', ['class' => 'col-md-9'])
                ->add('name', TextType::class, ['label' => 'Название'])
            ->end()
            ->with('Изображение', ['class' => 'col-md-3'])
                ->add('imgFile', FileType::class, $fileFieldOptions)
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('name', 'text', ['label' => 'Название', 'header_class' => 'col-md-12', 'route' => ['name' => 'edit']]);
    }

    public function toString($object): string
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
