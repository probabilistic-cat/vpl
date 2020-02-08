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
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $root = $this->getRoot();

        if ($root instanceof ProductAdmin) {
            $this->setFormMapperProductPage($formMapper);
        } else if ($root instanceof SubcategoryAdmin) {
            $this->setFormMapperSubcategoryPage($formMapper);
        }
    }

    /**
     * @param FormMapper $formMapper
     */
    private function setFormMapperProductPage(FormMapper $formMapper)
    {
        $object = $this->getSubject();
        $container = $this->getConfigurationPool()->getContainer();
        $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/' . $object->getImg();
        $fileFieldOptions = [
            'help' => '<img src="' . $fullPath
                . '" class="admin-product-preview" style="max-height: 300px; max-width: 300px;" />',
            'required' => false,
            'label' => 'Изображение (на странице подкатегории)'
        ];

        $formMapper
            ->tab('Продукт')
                ->with('Подкатегория', ['class' => 'col-md-12'])
                    ->add('subcategory', EntityType::class, [
                            'class' => Entity\Subcategory::class,
                            'choice_label' => 'name',
                            'label' => 'Подкатегория'
                        ]
                    )
                ->end()
                ->with('Продукт', ['class' => 'col-md-9'])
                    ->add('name', Type\TextType::class, ['label' => 'Название'])
                    ->add('description', Type\TextareaType::class, ['label' => 'Описание (на странице подкатегории)'])
                    ->add('description_full', Type\TextareaType::class, ['label' => 'Полное описание (на странице продукта)'])
                    ->add('seals', Type\TextType::class, ['label' => 'Dichtungen', 'required' => false])
                    ->add('chambers', Type\TextType::class, ['label' => 'Kammern', 'required' => false])
                    ->add('chambers_name', Type\TextType::class, ['label' => 'Название Kammern'])
                ->end()
                ->with('Изображение', ['class' => 'col-md-3'])
                    ->add('imgFile', Type\FileType::class, $fileFieldOptions)
                ->end()
            ->end()
            ->tab('Типы')
                ->with('Типы продукта')
                    ->add('productTypes', SonataCollectionType::class,
                        array(
                            'by_reference' => false,
                            'required' => false,
                            'label' => 'Типы продукта',
                            'btn_add' => 'Добавить',
                        ),
                        array(
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable' => 'seq',
                        )
                    )
                ->end()
            ->end()
            ->tab('Свойства')
                ->with('Свойства продукта')
                    ->add('productProperties', SonataCollectionType::class,
                        array(
                            'by_reference' => false,
                            'required' => false,
                            'label' => 'Свойства продукта',
                            'btn_add' => 'Добавить',
                        ),
                        array(
                            'edit' => 'inline',
                            'inline' => 'table',
                        )
                    )
                ->end()
            ->end()
            ->tab('Средний инфоблок')
                ->with('Средний инфоблок')
                    ->add('productInfoMiddles', SonataCollectionType::class,
                        array(
                            'by_reference' => false,
                            'required' => false,
                            'label' => 'Средний инфоблок',
                            'btn_add' => 'Добавить',
                        ),
                        array(
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable' => 'seq',
                        )
                    )
                ->end()
            ->end()
            ->tab('Нижний инфоблок')
                ->with('Нижний инфоблок')
                    ->add('productInfoBottoms', SonataCollectionType::class,
                        array(
                            'by_reference' => false,
                            'required' => false,
                            'label' => 'Нижний инфоблок',
                            'btn_add' => 'Добавить',
                            'help' => '<span id="spanProductInfoBottoms"></span>',
                        ),
                        array(
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable' => 'seq',
                        )
                    )
                ->end()
            ->end()
            ->tab('Производители')
                ->with('Производители продукта')
                    ->add('productManufacturers', SonataCollectionType::class,
                        array(
                            'by_reference' => false,
                            'required' => false,
                            'label' => 'Производители продукта',
                            'btn_add' => 'Добавить',
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

    /**
     * @param FormMapper $formMapper
     */
    private function setFormMapperSubcategoryPage(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', Type\TextType::class, ['label' => 'Название', 'attr' => ['readonly' => true]])
            ->add('seq', Type\TextType::class, ['label' => 'Последовательность']);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('subcategory', null, array(), EntityType::class, [
                    'class' => Entity\Subcategory::class,
                    'choice_label' => 'name'
                ]
            )
            ->add('subcategory.category', null, array(), EntityType::class, [
                    'class' => Entity\Category::class,
                    'choice_label' => 'name'
                ]
            );
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('subcategory.category.name', 'text', ['label' => 'Категория', 'header_class' => 'col-md-3'])
            ->add('subcategory.name', 'text', ['label' => 'Подкатегория', 'header_class' => 'col-md-3'])
            ->addIdentifier('name', 'text', ['label' => 'Название', 'header_class' => 'col-md-6']);
    }

    public function toString($object)
    {
        return $object instanceof Entity\Product
            ? $object->getName()
            : 'Product';
    }

    public function prePersist($object)
    {
        $this->manageImgFileUpload($object);

        $repo = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager()
            ->getRepository(Entity\Product::class);
        $seq = $repo->getSeqForNewProduct($object);
        $object->setSeq($seq);
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
