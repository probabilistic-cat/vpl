<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\CollectionType as SonataCollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class ProductAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        $container = $this->getConfigurationPool()->getContainer();
        $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/' . $object->getImg();
        $fileFieldOptions['help'] = '<img src="' . $fullPath . '" class="admin-subcategory-preview" />';
        $fileFieldOptions['required'] = false;

        $formMapper
            ->tab('Продукт')
                ->with('Подкатегория', ['class' => 'col-md-12'])
                    ->add('subcategory', EntityType::class, [
                            'class' => Entity\Subcategory::class,
                            'choice_label' => 'name',
                        ]
                    )
                ->end()
                ->with('Продукт', ['class' => 'col-md-9'])
                    ->add('name', Type\TextType::class)
                    ->add('description', Type\TextareaType::class)
                    ->add('description_full', Type\TextareaType::class)
                    ->add('seals', Type\TextType::class)
                    ->add('chambers', Type\TextType::class)
                ->end()
                ->with('Изображение', ['class' => 'col-md-3'])
                    ->add('imgFile', Type\FileType::class, $fileFieldOptions)
                ->end()
            ->end()
            ->tab('Типы продукта')
                ->add('productTypes', SonataCollectionType::class,
                    array(
                        'by_reference' => false,
                        'required' => false,
                    ),
                    array(
                        'edit' => 'inline',
                        'inline' => 'table',
                        'sortable' => 'seq',
                        //'allow_add' => true,
                    )
                )
                ->end()
            ->end()
            ->tab('Свойства')
                ->add('productProperties', SonataCollectionType::class,
                    array(
                        'by_reference' => false,
                        'required' => false,
                    ),
                    array(
                        'edit' => 'inline',
                        'inline' => 'table',
                        //'sortable' => 'seq',
                        //'allow_add' => true,
                    )
                )
                /*->add('productProperties', EntityType::class, [
                        'class' => Entity\ProductProperty::class,
                        'required' => false,
                        'multiple' => true,
                        'expanded' => true,
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('pp')
                                ->innerJoin('pp.categoryProperty', 'cp')
                                ->innerJoin('cp.property', 'p')
                                ->where('p.name = :propertyName')
                                ->setParameter('propertyName', Entity\Property::NAME_MODEL);
                        },
                    ]
                )*/
                ->end()
            ->end()
            ->tab('Инфоблоки')
                ->with('Средний блок')
                    ->add('productInfoMiddles', SonataCollectionType::class,
                        array(
                            'by_reference' => false,
                            'required' => false,
                        ),
                        array(
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable' => 'seq',
                        )
                    )
                ->end()
                ->with('Нижний блок')
                    ->add('productInfoBottoms', SonataCollectionType::class,
                        array(
                            'by_reference' => false,
                            'required' => false,
                        ),
                        array(
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable' => 'seq',
                        )
                    )
                ->end()
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('subcategory', null, array(), EntityType::class, [
                    'class' => Entity\Category::class,
                    'choice_label' => 'name',
                ]
            );
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('subcategory.name')
            ->addIdentifier('name');
    }

    /*public function prePersist($object)
    {
        foreach ($object->getProductTypes() as $productType) {
            $productType->setProduct($object);
        }
    }

    public function preUpdate($object)
    {
        foreach ($object->getProductTypes() as $productType) {
            $productType->setProduct($object);
        }
    }*/

    public function toString($object)
    {
        return $object instanceof Entity\Product
            ? $object->getName()
            : 'Product';
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
