<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type;

class ProductAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $product = $this->getSubject();
        $productTypes = $container->get('doctrine')->getRepository(Entity\ProductType::class)->findByProduct($product);

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
                    ->add('img', Type\TextareaType::class)
                ->end()
            ->end()
            ->tab('Типы продукта');

        /*foreach ($productTypes as $productType) {
            $this->setSubject($productType);
            $formMapper
                ->with('Тип ' . $productType->getSeq(), ['class' => 'col-md-12'])
                    ->add($productType->getText(), Type\TextType::class)
                ->end();

        }*/

        $formMapper
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

    public function toString($object)
    {
        return $object instanceof Entity\Product
            ? $object->getName()
            : 'Product';
    }
}
