<?php

namespace AppBundle\Admin;

use AppBundle\Entity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type;

class UserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', Type\TextType::class)
            ->add('mail', Type\TextType::class)
            ->add('password', Type\PasswordType::class)
            ->add('role', Type\TextType::class)
            ->add('active', Type\CheckboxType::class);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('mail')
            ->add('role')
            ->add('active');
    }

    public function prePersist($user)
    {
        $this->setEnctyptedPassword($user);
    }

    public function preUpdate($user)
    {
        $this->setEnctyptedPassword($user);
    }

    private function setEnctyptedPassword(Entity\User $user)
    {
        if (empty($user->getPassword())) {
            $em = $this->getModelManager()->getEntityManager($this->getClass());
            $original = $em->getUnitOfWork()->getOriginalDocumentData($user);
            $user->setPassword($original->getPassword());
            return;
        }

        $container = $this->getConfigurationPool()->getContainer();
        $passwordEncoder = $container->get('security.password_encoder');
        $password = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
    }
}
