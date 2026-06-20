<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Property;
use App\Entity\PropertySet;
use App\Repository\PropertyRepository;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\Form\Type\CollectionType as SonataCollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PropertySetAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void {
        $propertiesWithoutDescQBFn = static fn (PropertyRepository $repo): QueryBuilder =>
            $repo->getQBWithoutDesc()
        ;
        $form
            ->tab('Набор свойств')
                ->with('Наборы')
                    ->add('property', EntityType::class, [
                        'class' => Property::class,
                        'query_builder' => $propertiesWithoutDescQBFn,
                        'choice_label' => 'name',
                        'label' => 'Свойство',
                    ])
                    ->add('name', TextType::class, ['label' => 'Название'])
                ->end()
            ->end()
            ->tab('Элементы набора')
                ->with('Элементы набора')
                    ->add('propertyItems', SonataCollectionType::class, [
                        'by_reference' => false,
                        'required' => false,
                        'label' => 'Элементы набора',
                        'btn_add' => 'Добавить',
                    ], ['edit' => 'inline', 'inline' => 'table'])
                ->end()
            ->end()
        ;
    }

    protected function configureListFields(ListMapper $list): void {
        $list
            ->add('property.name', 'text', ['label' => 'Свойство', 'header_class' => 'col-md-3'])
            ->addIdentifier('name', 'text', [
                'label' => 'Название',
                'header_class' => 'col-md-8',
                'route' => ['name' => 'edit'],
            ])
            ->add('_actions', null, [
                'label' => 'Действия', 'header_class' => 'col-md-1',
                'actions' => ['clone' => ['template' => 'crud/list_action_clone.html.twig']],
            ])
        ;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void {
        $collection
            ->add('clone', $this->getRouterIdParameter() . '/clone')
        ;
    }

    #[\Override]
    public function toString(object $object): string {
        /** @var PropertySet $object */
        return $object->getName();
    }
}
