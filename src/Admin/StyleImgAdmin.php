<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type;

class StyleImgAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $styleImg = $this->getSubject();
        $imgOptions = [
            'required' => false,
            'label' => 'Изображение'
        ];
        $imgColorOptions = [
            'required' => false,
            'label' => 'Гамма'
        ];

        if (!is_null($styleImg)) {
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/'
                . $styleImg->getImg();
            $imgOptions['help'] = '<img src="' . $fullPath . '" class="admin-style-img-preview" '
                . 'style="max-height: 200px; max-width: 600px;" />';

            $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/'
                . $styleImg->getImgColor();
            $imgColorOptions['help'] = '<img src="' . $fullPath . '" class="admin-style-img-color-preview" '
                . 'style="max-height: 100px; max-width: 100px;" />';
        }

        $formMapper
            ->add('imgFile', Type\FileType::class, $imgOptions)
            ->add('imgColorFile', Type\FileType::class, $imgColorOptions)
            ->add('seq', Type\TextType::class, ['label' => 'Последовательность']);;
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
        if ($object->getImgFile() || $object->getImgColorFile()) {
            $object->refreshUpdated();
        }
    }
}
