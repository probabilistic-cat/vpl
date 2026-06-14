<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\ProductType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductInfoMiddleGalleryAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper): void {
        $object = $this->getSubject();
        $fileFieldOptions = [
            'required' => false,
            'label' => 'Изображение',
        ];

        if (!is_null($object)) {
            $fullPath = '/' . $object->getImg();
            $fileFieldOptions = [
                'help' => '<img src="' . $fullPath . '" class="admin-product-property-preview" '
                . 'style="max-height: 100px; max-width: 100px;" />',
                'help_html' => true,
            ];
        }

        $formMapper
            ->add('imgFile', FileType::class, $fileFieldOptions)
            ->add('seq', TextType::class, ['label' => 'Посл.']);
    }

    #[\Override]
    public function toString($object): string {
        return $object instanceof ProductType
            ? $object->getName()
            : 'ProductType';
    }

    public function prePersist($object): void {
        $this->manageImgFileUpload($object);
    }

    public function preUpdate($object): void {
        $this->manageImgFileUpload($object);
    }

    private function manageImgFileUpload($object): void {
        if ($object->getImgFile()) {
            $object->refreshUpdated();
        }
    }
}
