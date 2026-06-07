<?php

namespace App\Admin;

use App\Entity;
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
            ->add('name', Type\TextType::class, ['label' => 'Имя'])
            ->add('mail', Type\TextType::class, ['label' => 'Email'])
            ->add('password', Type\PasswordType::class, ['label' => 'Пароль'])
            ->add('role', Type\TextType::class, ['label' => 'Роли'])
            ->add('active', Type\CheckboxType::class, ['label' => 'Активен']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', 'text', ['label' => 'Имя', 'header_class' => 'col-md-4'])
            ->add('mail', 'text', ['label' => 'Email', 'header_class' => 'col-md-4'])
            ->add('role', 'text', ['label' => 'Роли', 'header_class' => 'col-md-3'])
            ->add('active', null, ['label' => 'Активен', 'header_class' => 'col-md-1']);
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
