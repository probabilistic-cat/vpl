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
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher, private readonly EntityManagerInterface $em) {}

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
            ->add('name', TextType::class, ['label' => 'Имя'])
            ->add('mail', TextType::class, ['label' => 'Email'])
            ->add('password', PasswordType::class, ['required' => false, 'label' => 'Пароль'])
            ->add('role', TextType::class, ['label' => 'Роли'])
            ->add('active', CheckboxType::class, ['required' => false, 'label' => 'Активен']);
    }

    protected function configureListFields(ListMapper $listMapper): void {
        $listMapper
            ->addIdentifier('name', 'text', ['label' => 'Имя', 'header_class' => 'col-md-4', 'route' => ['name' => 'edit']])
            ->add('mail', 'text', ['label' => 'Email', 'header_class' => 'col-md-4'])
            ->add('role', 'text', ['label' => 'Роли', 'header_class' => 'col-md-3'])
            ->add('active', null, ['label' => 'Активен', 'header_class' => 'col-md-1']);
    }

    public function prePersist($user): void {
        $this->setEnctyptedPassword($user);
    }

    public function preUpdate($user): void {
        $this->setEnctyptedPassword($user);
    }

    private function setEnctyptedPassword(User $user): void {
        $password = $user->getPassword();
        if (self::passwordWasNotChanged($password)) {
            $uow = $this->em->getUnitOfWork();
            $originalData = $uow->getOriginalEntityData($user);
            $originalPassword = $originalData['password'] ?? null;
            $user->setPassword($originalPassword);
            return;
        }

        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
    }

    private static function passwordWasNotChanged(?string $password): bool {
        return $password === null || $password === '';
    }
}
