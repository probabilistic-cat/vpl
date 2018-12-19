<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type;

class ProductTypeAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        $container = $this->getConfigurationPool()->getContainer();
        $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/' . $object->getImg();
        $fileFieldOptions['help'] = '<img src="' . $fullPath
            . '" class="admin-product-type-preview" style="max-height: 200px; max-width: 200px;" />';
        $fileFieldOptions['required'] = false;

        $formMapper
            ->add('text', Type\TextareaType::class)
            ->add('imgFile', Type\FileType::class, $fileFieldOptions)
            ->add('seq', Type\NumberType::class);
    }

    public function toString($object)
    {
        return $object instanceof Entity\ProductType
            ? $object->getName()
            : 'ProductType';
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
        if ($object->getImgFile()) {
            $object->refreshUpdated();
        }
    }
}
