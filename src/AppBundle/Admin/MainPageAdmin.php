<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class MainPageAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        $container = $this->getConfigurationPool()->getContainer();

        $secondLine2ImgPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/'
            . $object->getSecondLine2Img();
        $secondLine2ImgOptions = [
            'help' => '<img src="' . $secondLine2ImgPath
                . '" class="admin-secondline2-preview" style="max-height: 300px; max-width: 300px;" />',
            'required' => false,
            'label' => 'Блок 2. Изображение'
        ];

        $fourthLine2ImgPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/'
            . $object->getFourthLine2Img();
        $fourthLine2ImgOptions = [
            'help' => '<img src="' . $fourthLine2ImgPath
                . '" class="admin-fourthline2-preview" style="max-height: 300px; max-width: 300px;" />',
            'required' => false,
            'label' => 'Блок 2. Изображение'
        ];

        $fourthLine3ImgPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/'
            . $object->getFourthLine3Img();
        $fourthLine3ImgOptions = [
            'help' => '<img src="' . $fourthLine3ImgPath
                . '" class="admin-fourthline3-preview" style="max-height: 300px; max-width: 300px;" />',
            'required' => false,
            'label' => 'Блок 3. Изображение'
        ];

        $formMapper
            //->tab('Главная страница')
            ->with('Контактная информация', ['class' => 'col-md-12'])
                ->add('phone', Type\TextType::class, ['label' => 'Телефон', 'required' => false])
                ->add('mail', Type\TextType::class, ['label' => 'Email', 'required' => false])
                ->add('facebook', Type\TextType::class, ['label' => 'Ссылка на Facebook', 'required' => false])
                ->add('copyright', Type\TextType::class, ['label' => 'Copyright', 'required' => false])
                /*->add('address', Type\TextType::class,
                    ['label' => 'Адрес (на странице контактов)', 'required' => false])
                ->add('map_src', Type\TextareaType::class,
                    ['label' => 'Адрес на Google карте (на странице контактов)', 'required' => false])*/
            ->end()
            /*->with('Первая строка', ['class' => 'col-md-12'])
                ->add('productTypes', SonataCollectionType::class,
                        array(
                            'by_reference' => false,
                            'required' => false,
                            'label' => 'Первая строка',
                            'btn_add' => 'Добавить',
                        ),
                        array(
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable' => 'seq',
                        )
                    )
            ->end()*/
            ->with('Вторая строка', ['class' => 'col-md-12'])
                /*->with('Первый блок', ['class' => 'col-md-4'])
                ->end()
                ->with('Второй блок', ['class' => 'col-md-4'])
                ->end()
                ->with('Третий блок', ['class' => 'col-md-4'])
                ->end()*/
                ->add('secondLine1', EntityType::class, [
                        'class' => Entity\Product::class,
                        'choice_label' => 'name',
                        'label' => 'Блок 1. Продукт',
                        'required' => false
                    ]
                )
                ->add('secondLine2ImgFile', Type\FileType::class, $secondLine2ImgOptions)
                ->add('second_line_3_header', Type\TextType::class,
                    ['label' => 'Блок 3. Заголовок', 'required' => false])
                ->add('second_line_3_text', Type\TextareaType::class,
                    ['label' => 'Блок 3. Текст', 'required' => false])
            ->end()
            ->with('Третья строка', ['class' => 'col-md-12'])
                ->add('thirdLine1', EntityType::class, [
                        'class' => Entity\Product::class,
                        'choice_label' => 'name',
                        'label' => 'Блок 1. Продукт',
                        'required' => false
                    ]
                )
            ->end()
            ->with('Четвертая строка', ['class' => 'col-md-12'])
                ->add('fourth_line_1_header', Type\TextType::class,
                    ['label' => 'Блок 1. Заголовок', 'required' => false])
                ->add('fourth_line_1_text', Type\TextareaType::class,
                    ['label' => 'Блок 1. Текст', 'required' => false])
                ->add('fourthLine2ImgFile', Type\FileType::class, $fourthLine2ImgOptions)
                ->add('fourth_line_2_header', Type\TextType::class,
                    ['label' => 'Блок 2. Заголовок', 'required' => false])
                ->add('fourth_line_2_text', Type\TextareaType::class,
                    ['label' => 'Блок 2. Текст', 'required' => false])
                ->add('fourthLine3ImgFile', Type\FileType::class, $fourthLine3ImgOptions)
                ->add('fourth_line_3_header', Type\TextType::class,
                    ['label' => 'Блок 3. Заголовок', 'required' => false])
                ->add('fourth_line_3_text', Type\TextareaType::class,
                    ['label' => 'Блок 3. Текст', 'required' => false])
            ->end();
            /*->end()
            ->tab('Большие изображения')
            ->end();*/
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', 'text', ['label' => 'Главная страница', 'header_class' => 'col-md-12']);
    }

    public function toString($object)
    {
        return 'MainPage';
    }

    public function prePersist($object)
    {
        $this->manageImgFileUpload($object);
    }

    public function preUpdate($object)
    {
        $this->manageImgFileUpload($object);
    }

    private function manageImgFileUpload($object)
    {
        if ($object->getSecondLine2ImgFile() || $object->getFourthLine2ImgFile() || $object->getFourthLine3ImgFile()) {
            $object->refreshUpdated();
        }
    }
}
