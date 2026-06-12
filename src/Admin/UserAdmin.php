<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', TextType::class, ['label' => 'Имя'])
            ->add('mail', TextType::class, ['label' => 'Email'])
            ->add('password', PasswordType::class, ['label' => 'Пароль'])
            ->add('role', TextType::class, ['label' => 'Роли'])
            ->add('active', CheckboxType::class, ['label' => 'Активен']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', 'text', ['label' => 'Имя', 'header_class' => 'col-md-4'])
            ->add('mail', 'text', ['label' => 'Email', 'header_class' => 'col-md-4'])
            ->add('role', 'text', ['label' => 'Роли', 'header_class' => 'col-md-3'])
            ->add('active', null, ['label' => 'Активен', 'header_class' => 'col-md-1']);
    }

    public function prePersist($user): void
    {
        $this->setEnctyptedPassword($user);
    }

    public function preUpdate($user): void
    {
        $this->setEnctyptedPassword($user);
    }

    private function setEnctyptedPassword(User $user): void
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
