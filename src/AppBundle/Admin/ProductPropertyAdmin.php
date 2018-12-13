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
        if (!is_null($object)) {
            $this->category = $object->getProduct()->getSubcategory()->getCategory();
        }

        $formMapper
            ->add('categoryProperty', EntityType::class, [
                    'class' => Entity\CategoryProperty::class,
                    'query_builder' => function (Repository\CategoryPropertyRepository $repo) {
                        return $repo->createCategoryImgQueryBuilder($this->category);
                    },
                    'choice_label' => 'property.name'
                ]
            )
            ->add('img', Type\TextType::class)
            ->add('seq', Type\TextType::class);
    }
}
