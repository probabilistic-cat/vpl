<?php

declare(strict_types=1);

namespace App\Admin;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;

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
            ->add('imgFile', FileType::class, $imgOptions)
            ->add('imgColorFile', FileType::class, $imgColorOptions)
            ->add('seq', TextType::class, ['label' => 'Последовательность']);;
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
        if ($object->getImgFile() || $object->getImgColorFile()) {
            $object->refreshUpdated();
        }
    }
}
