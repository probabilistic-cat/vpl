<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use AppBundle\Repository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class ProductPropertyAdmin extends AbstractAdmin
{
    /**
     * @var Entity\Category
     */
    private $category;

    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        $container = $this->getConfigurationPool()->getContainer();
        $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/' . $object->getImg();
        $fileFieldOptions = [
            'help' => '<img src="' . $fullPath
                . '" class="admin-product-property-preview" style="max-height: 100px; max-width: 100px;" />',
            'required' => false,
            'label' => 'Изображение'
        ];

        if (!is_null($object)) {
            $this->category = $object->getProduct()->getSubcategory()->getCategory();
        }

        $formMapper
            ->add('categoryProperty', EntityType::class, [
                    'class' => Entity\CategoryProperty::class,
                    'query_builder' => function (Repository\CategoryPropertyRepository $repo) {
                        return $repo->createCategoryImgQueryBuilder($this->category);
                    },
                    'choice_label' => 'property.name',
                    'label' => 'Свойство'
                ]
            )
            ->add('imgFile', Type\FileType::class, $fileFieldOptions)
            ->add('seq', Type\TextType::class, ['label' => 'Последовательность']);
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
