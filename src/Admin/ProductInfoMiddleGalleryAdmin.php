<?php

declare(strict_types=1);

namespace App\Admin;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\ProductType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class ProductInfoMiddleGalleryAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        $fileFieldOptions = [
            'required' => false,
            'label' => 'Изображение'
        ];

        if (!is_null($object)) {
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/' . $object->getImg();
            $fileFieldOptions['help'] = '<img src="' . $fullPath . '" class="admin-product-property-preview" '
                . 'style="max-height: 100px; max-width: 100px;" />';
        }

        $formMapper
            ->add('imgFile', FileType::class, $fileFieldOptions)
            ->add('seq', TextType::class, ['label' => 'Посл.']);
    }

    public function toString($object)
    {
        return $object instanceof ProductType
            ? $object->getName()
            : 'ProductType';
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
