<?php

declare(strict_types=1);

namespace App\Admin;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class MiscAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        $container = $this->getConfigurationPool()->getContainer();

        $designImgPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/'
            . $object->getDesignImg();
        $designImgOptions = [
            'help' => '<img src="' . $designImgPath
                . '" class="admin-design-img-preview" style="max-height: 300px; max-width: 300px;" />',
            'required' => false,
            'label' => 'Иконка дизайна'
        ];

        $formMapper
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
                ->add('contact_address', TextType::class,
                    ['label' => 'Адрес (на странице контактов)', 'required' => false])
                ->add('contact_map_src', TextareaType::class,
                    ['label' => 'Адрес на Google карте (на странице контактов)', 'required' => false])
            ->end();
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', 'text', ['label' => 'Главная страница', 'header_class' => 'col-md-12']);
    }

    public function toString($object)
    {
        return 'Misc';
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
        if ($object->getDesignImgFile()) {
            $object->refreshUpdated();
        }
    }
}
