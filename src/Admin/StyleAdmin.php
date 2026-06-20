<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Style;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType as SonataCollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StyleAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void {
        $form
            ->tab('Стиль')
                ->with('Стиль')
                    ->add('name', TextType::class, ['label' => 'Название'])
                    ->add('seq', TextType::class, ['label' => 'Последовательность'])
                ->end()
            ->end()
            ->tab('Изображения')
                ->with('Изображения')
                    ->add('styleImgs', SonataCollectionType::class, [
                        'by_reference' => false,
                        'required' => false,
                        'label' => 'Изображения',
                        'btn_add' => 'Добавить',
                    ], ['edit' => 'inline', 'inline' => 'standard'])
                ->end()
            ->end()
            ->tab('Нижний инфоблок')
                ->with('Нижний инфоблок')
                    ->add('styleInfoBottoms', SonataCollectionType::class, [
                        'by_reference' => false,
                        'required' => false,
                        'label' => 'Нижний инфоблок',
                        'btn_add' => 'Добавить',
                        'help' => '<span id="spanStyleInfoBottoms"></span>',
                        'help_html' => true,
                    ], ['edit' => 'inline', 'inline' => 'table', 'sortable' => 'seq'])
                ->end()
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
        /** @var Style $object */
        return $object->getName();
    }
}
