<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\MainPage;
use App\Entity\Product;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MainPageAdmin extends AbstractAdmin
{
    use CommonAdmin;

    protected function configureFormFields(FormMapper $form): void {
        /** @var MainPage $mainPage */
        $mainPage = $this->getSubject();

        $form
            //->tab('Главная страница')
            ->with('Контактная информация', ['class' => 'col-md-12'])
                ->add('phone', TextType::class, ['label' => 'Телефон', 'required' => false])
                ->add('mail', TextType::class, ['label' => 'Email', 'required' => false])
                ->add('facebook', TextType::class, ['label' => 'Ссылка на Facebook', 'required' => false])
                ->add('copyright', TextType::class, ['label' => 'Copyright', 'required' => false])

                //->add('address', TextType::class, ['label' => 'Адрес (на странице контактов)', 'required' => false])
                //->add('map_src', TextareaType::class, [
                //    'label' => 'Адрес на Google карте (на странице контактов)',
                //    'required' => false],
                //)
            ->end()
            //->with('Первая строка', ['class' => 'col-md-12'])
            //    ->add('productTypes', SonataCollectionType::class, [
            //        'by_reference' => false,
            //        'required' => false,
            //        'label' => 'Первая строка',
            //        'btn_add' => 'Добавить',
            //    ], [
            //        'edit' => 'inline',
            //        'inline' => 'table',
            //        'sortable' => 'seq',
            //    ])
            //->end()
            ->with('Вторая строка', ['class' => 'col-md-12'])
                //->with('Первый блок', ['class' => 'col-md-4'])
                //->end()
                //->with('Второй блок', ['class' => 'col-md-4'])
                //->end()
                //->with('Третий блок', ['class' => 'col-md-4'])
                //->end()
                ->add('secondLine1', EntityType::class, [
                    'class' => Product::class,
                    'choice_label' => 'name',
                    'label' => 'Блок 1. Продукт',
                    'required' => false,
                ])
                ->add('secondLine2ImgFile', FileType::class, $this->getFormImageOptions(
                    '<img src="/' . $mainPage->secondLine2Img
                    . '" class="admin-secondline2-preview" style="max-height: 300px; max-width: 300px;" />',
                    'Блок 2. Изображение',
                ))
                ->add('secondLine3Header', TextType::class, ['label' => 'Блок 3. Заголовок', 'required' => false])
                ->add('secondLine3Text', TextareaType::class, ['label' => 'Блок 3. Текст', 'required' => false])
            ->end()
            ->with('Третья строка', ['class' => 'col-md-12'])
                ->add('thirdLine1', EntityType::class, [
                    'class' => Product::class,
                    'choice_label' => 'name',
                    'label' => 'Блок 1. Продукт',
                    'required' => false,
                ])
            ->end()
            ->with('Четвертая строка', ['class' => 'col-md-12'])
                ->add('fourthLine1Header', TextType::class, ['label' => 'Блок 1. Заголовок', 'required' => false])
                ->add('fourthLine1Text', TextareaType::class, ['label' => 'Блок 1. Текст', 'required' => false])
                ->add('fourthLine2ImgFile', FileType::class, $this->getFormImageOptions(
                    '<img src="/' . $mainPage->fourthLine2Img
                    . '" class="admin-fourthline2-preview" style="max-height: 300px; max-width: 300px;" />',
                    'Блок 2. Изображение',
                ))
                ->add('fourthLine2Header', TextType::class, ['label' => 'Блок 2. Заголовок', 'required' => false])
                ->add('fourthLine2Text', TextareaType::class, ['label' => 'Блок 2. Текст', 'required' => false])
                ->add('fourthLine3ImgFile', FileType::class, $this->getFormImageOptions(
                    '<img src="/' . $mainPage->fourthLine3Img
                    . '" class="admin-fourthline3-preview" style="max-height: 300px; max-width: 300px;" />',
                    'Блок 3. Изображение',
                ))
                ->add('fourthLine3Header', TextType::class, ['label' => 'Блок 3. Заголовок', 'required' => false])
                ->add('fourthLine3Text', TextareaType::class, ['label' => 'Блок 3. Текст', 'required' => false])
            ->end()
            //->end()
            //->tab('Большие изображения')
            //->end()
        ;
    }

    protected function configureListFields(ListMapper $list): void {
        $list->addIdentifier('id', 'text', [
            'label' => 'Главная страница',
            'header_class' => 'col-md-12',
            'route' => ['name' => 'edit'],
        ]);
    }

    #[\Override]
    public function toString(object $object): string {
        /** @var MainPage $object */
        return 'MainPage ' . $object->id;
    }
}
