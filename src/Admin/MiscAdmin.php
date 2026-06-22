<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Misc;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MiscAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void {
        /** @var Misc $misc */
        $misc = $this->getSubject();
        $designImgHtml = '<img src="/' . $misc->designImg
            . '" class="admin-design-img-preview" style="max-height: 300px; max-width: 300px;" />'
        ;
        $designImgOptions = [
            'help' => $designImgHtml,
            'help_html' => true,
            'required' => false,
            'label' => 'Иконка дизайна',
        ];

        $form
            ->with('Дизайн', ['class' => 'col-md-12'])
                ->add('design_name', TextType::class, ['label' => 'Название', 'required' => true])
                ->add('design_description', TextType::class, ['label' => 'Описание', 'required' => false])
                ->add('designImgFile', FileType::class, $designImgOptions)
            ->end()
            ->with('Страница категорий', ['class' => 'col-md-12'])
                ->add('categories_name', TextType::class, ['label' => 'Название', 'required' => true])
                ->add('categories_description', TextType::class, ['label' => 'Описание', 'required' => false])
            ->end()
            ->with('Контакты', ['class' => 'col-md-12'])
                ->add('contact_address', TextType::class, [
                    'label' => 'Адрес (на странице контактов)',
                    'required' => false,
                ])
                ->add('contact_map_src', TextareaType::class, [
                    'label' => 'Адрес на Google карте (на странице контактов)',
                    'required' => false,
                ])
            ->end()
        ;
    }

    protected function configureListFields(ListMapper $list): void {
        $list->addIdentifier('id', 'text', [
            'label' => 'Главная страница',
            'header_class' => 'col-md-12',
            'route' => ['name' => 'edit']],
        );
    }

    #[\Override]
    public function toString(object $object): string {
        /** @var Misc $object */
        return 'Misc ' . $object->getId();
    }
}
