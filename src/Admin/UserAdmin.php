<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAdmin extends AbstractAdmin
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $em,
    ) {}

    protected function configureFormFields(FormMapper $form): void {
        $form
            ->add('name', TextType::class, ['label' => 'Имя'])
            ->add('mail', TextType::class, ['label' => 'Email'])
            ->add('password', PasswordType::class, ['required' => false, 'label' => 'Пароль'])
            ->add('role', TextType::class, ['label' => 'Роли'])
            ->add('active', CheckboxType::class, ['required' => false, 'label' => 'Активен'])
        ;
    }

    protected function configureListFields(ListMapper $list): void {
        $list
            ->addIdentifier('name', 'text', [
                'label' => 'Имя',
                'header_class' => 'col-md-4',
                'route' => ['name' => 'edit'],
            ])
            ->add('mail', 'text', ['label' => 'Email', 'header_class' => 'col-md-4'])
            ->add('role', 'text', ['label' => 'Роли', 'header_class' => 'col-md-3'])
            ->add('active', null, ['label' => 'Активен', 'header_class' => 'col-md-1'])
        ;
    }

    #[\Override]
    public function toString(object $object): string {
        /** @var User $object */
        return $object->name;
    }

    protected function prePersist(object $object): void {
        /** @var User $object */
        $this->setEnctyptedPassword($object);
    }

    protected function preUpdate(object $object): void {
        /** @var User $object */
        $this->setEnctyptedPassword($object);
    }

    private function setEnctyptedPassword(User $user): void {
        $password = $user->password;
        if (self::passwordWasNotChanged($password)) {
            $uow = $this->em->getUnitOfWork();
            $originalData = $uow->getOriginalEntityData($user);
            $originalPassword = $originalData['password'] ?? null;
            $user->password = $originalPassword;
            return;
        }

        $user->password = $this->passwordHasher->hashPassword($user, $user->password);
    }

    private static function passwordWasNotChanged(?string $password): bool {
        return $password === null || $password === '';
    }
}
