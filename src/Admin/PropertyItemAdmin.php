<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PropertyItemAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $propertyItem = $this->getSubject();
        $fileFieldOptions = [
            'required' => false,
            'label' => 'Изображение',
        ];

        if (!is_null($propertyItem)) {
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/'
                . $propertyItem->getImg();
            $fileFieldOptions['help'] = '<img src="' . $fullPath . '" class="admin-product-property-preview" '
                . 'style="max-height: 100px; max-width: 100px;" />';
        }

        $formMapper
            ->add('name', TextType::class, ['label' => 'Название', 'required' => false])
            ->add('imgFile', FileType::class, $fileFieldOptions)
            ->add('seq', TextType::class, ['label' => 'Последовательность']);
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
        if ($object->getImgFile()) {
            $object->refreshUpdated();
        }
    }
}
