<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Subcategory;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType as SonataCollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $root = $this->getRoot();

        if ($root instanceof ProductAdmin) {
            $this->setFormMapperProductPage($formMapper);
        } elseif ($root instanceof SubcategoryAdmin) {
            $this->setFormMapperSubcategoryPage($formMapper);
        }
    }

    private function setFormMapperProductPage(FormMapper $formMapper): void
    {
        $object = $this->getSubject();
        $container = $this->getConfigurationPool()->getContainer();
        $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath() . '/' . $object->getImg();
        $fileFieldOptions = [
            'help' => '<img src="' . $fullPath
                . '" class="admin-product-preview" style="max-height: 300px; max-width: 300px;" />',
            'required' => false,
            'label' => 'Изображение (на странице подкатегории)',
        ];

        $formMapper
            ->tab('Продукт')
                ->with('Подкатегория', ['class' => 'col-md-12'])
                    ->add('subcategory', EntityType::class, [
                            'class' => Subcategory::class,
                            'choice_label' => 'name',
                            'label' => 'Подкатегория',
                        ],
                    )
                ->end()
                ->with('Продукт', ['class' => 'col-md-9'])
                    ->add('name', TextType::class, ['label' => 'Название'])
                    ->add('description', TextareaType::class, ['label' => 'Описание (на странице подкатегории)'])
                    ->add('description_full', TextareaType::class, ['label' => 'Полное описание (на странице продукта)'])
                    ->add('seals', TextType::class, ['label' => 'Dichtungen', 'required' => false])
                    ->add('chambers', TextType::class, ['label' => 'Kammern', 'required' => false])
                    ->add('chambers_name', TextType::class, ['label' => 'Название Kammern'])
                ->end()
                ->with('Изображение', ['class' => 'col-md-3'])
                    ->add('imgFile', FileType::class, $fileFieldOptions)
                ->end()
            ->end()
            ->tab('Типы')
                ->with('Типы продукта')
                    ->add('productTypes', SonataCollectionType::class,
                        [
                            'by_reference' => false,
                            'required' => false,
                            'label' => 'Типы продукта',
                            'btn_add' => 'Добавить',
                        ],
                        [
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable' => 'seq',
                        ],
                    )
                ->end()
            ->end()
            ->tab('Свойства')
                ->with('Свойства продукта')
                    ->add('productProperties', SonataCollectionType::class,
                        [
                            'by_reference' => false,
                            'required' => false,
                            'label' => 'Свойства продукта',
                            'btn_add' => 'Добавить',
                        ],
                        [
                            'edit' => 'inline',
                            'inline' => 'table',
                        ],
                    )
                ->end()
            ->end()
            ->tab('Средний инфоблок')
                ->with('Средний инфоблок')
                    ->add('productInfoMiddles', SonataCollectionType::class,
                        [
                            'by_reference' => false,
                            'required' => false,
                            'label' => 'Средний инфоблок',
                            'btn_add' => 'Добавить',
                        ],
                        [
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable' => 'seq',
                        ],
                    )
                ->end()
            ->end()
            ->tab('Нижний инфоблок')
                ->with('Нижний инфоблок')
                    ->add('productInfoBottoms', SonataCollectionType::class,
                        [
                            'by_reference' => false,
                            'required' => false,
                            'label' => 'Нижний инфоблок',
                            'btn_add' => 'Добавить',
                            'help' => '<span id="spanProductInfoBottoms"></span>',
                        ],
                        [
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable' => 'seq',
                        ],
                    )
                ->end()
            ->end()
            ->tab('Производители')
                ->with('Производители продукта')
                    ->add('productManufacturers', SonataCollectionType::class,
                        [
                            'by_reference' => false,
                            'required' => false,
                            'label' => 'Производители продукта',
                            'btn_add' => 'Добавить',
                        ],
                        [
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable' => 'seq',
                        ],
                    )
                ->end()
            ->end();
    }

    private function setFormMapperSubcategoryPage(FormMapper $formMapper): void
    {
        $formMapper
            ->add('name', TextType::class, ['label' => 'Название', 'attr' => ['readonly' => true]])
            ->add('seq', TextType::class, ['label' => 'Последовательность']);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('subcategory', null, [], EntityType::class, [
                    'class' => Subcategory::class,
                    'choice_label' => 'name',
                ],
            )
            ->add('subcategory.category', null, [], EntityType::class, [
                    'class' => Category::class,
                    'choice_label' => 'name',
                ],
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
        return $object instanceof Product
            ? $object->getName()
            : 'Product';
    }

    public function prePersist($object): void
    {
        $this->manageImgFileUpload($object);

        $repo = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager()
            ->getRepository(Product::class);
        $seq = $repo->getSeqForNewProductInSubcategory($object->getSubcategory()->getId());
        $object->setSeq($seq);
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
