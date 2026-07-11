<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\MainPageImages;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MainPageImagesAdmin extends AbstractAdmin
{
    use CommonAdmin;

    protected function configureFormFields(FormMapper $form): void {
        /** @var MainPageImages $mainPageImages */
        $mainPageImages = $this->getSubject();

        $form
            ->add('imgFile', FileType::class, $this->getFormImageOptions(
                '<img src="/' . $mainPageImages->img
                . '" class="admin-firstline-preview" style="max-height: 300px; max-width: 500px;" />',
                'Изображение',
            ))
            ->add('header', TextType::class, ['label' => 'Заголовок', 'required' => false])
            ->add('text', TextareaType::class, ['label' => 'Текст', 'required' => false])
            ->add('seq', NumberType::class, ['label' => 'Последовательность'])
        ;
    }

    protected function configureListFields(ListMapper $list): void {
        $list
            ->addIdentifier('seq', 'text', [
                'label' => 'Номер',
                'header_class' => 'col-md-3',
                'route' => ['name' => 'edit'],
            ])
            ->add('header', 'text', ['label' => 'Заголовок', 'header_class' => 'col-md-9'])
        ;
    }

    #[\Override]
    public function toString(object $object): string {
        /** @var MainPageImages $object */
        return 'MainPageImage ' . $object->id;
    }
}
