<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Property;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PropertyAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void {
        $form
            ->with('Свойства')
                ->add('name', TextType::class, ['label' => 'Название'])
            ->end()
        ;
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
        /** @var Property $object */
        return $object->name;
    }
}
