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
    use CommonAdmin;

    protected function configureFormFields(FormMapper $form): void {
        /** @var Manufacturer $manufacturer */
        $manufacturer = $this->getSubject();

        $form
            ->with('Категория', ['class' => 'col-md-9'])
                ->add('name', TextType::class, ['label' => 'Название'])
            ->end()
            ->with('Изображение', ['class' => 'col-md-3'])
                ->add('imgFile', FileType::class, $this->getFormImageOptions(
                    '<img src="/' . $manufacturer->img
                    . '" class="admin-manufacturer-preview" style="max-height: 300px; max-width: 300px;" />',
                    'Изображение',
                ))
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
        /** @var Manufacturer $object */
        return $object->name;
    }
}
