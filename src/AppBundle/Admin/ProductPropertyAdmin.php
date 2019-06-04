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
        $productProperty = $this->getSubject();
        $product = $this->getRoot()->getSubject();
        $fileFieldOptions = [
            'required' => false,
            'label' => 'Изображение'
        ];

        $this->category = $product->getSubcategory()->getCategory();
        if (!is_null($productProperty)) {
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/'
                . $productProperty->getImg();
            $fileFieldOptions['help'] = '<img src="' . $fullPath . '" class="admin-product-property-preview" '
                . 'style="max-height: 100px; max-width: 100px;" />';
        }

        $formMapper
            ->add('categoryProperty', EntityType::class, [
                    'class' => Entity\CategoryProperty::class,
                    'query_builder' => function (Repository\CategoryPropertyRepository $repo) {
                        return $repo->createCategoryQueryBuilder($this->category);
                    },
                    'choice_label' => 'property.name',
                    'label' => 'Свойство'
                ]
            )
            ->add('name', Type\TextType::class, ['label' => 'Название'])
            ->add('propertySet', EntityType::class, [
                    'class' => Entity\PropertySet::class,
                    'choice_label' => 'name',
                    'label' => 'Набор свойств',
                    'required' => false,
                    //'disabled' => true,
                ]
            )
            ->add('imgFile', Type\FileType::class, $fileFieldOptions)
            ->add('seq', Type\TextType::class, ['label' => 'Последовательность', 'required' => true]);
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
