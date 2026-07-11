<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\StyleImg;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StyleImgAdmin extends AbstractAdmin
{
    use CommonAdmin;

    protected function configureFormFields(FormMapper $form): void {
        /** @var StyleImg $styleImg */
        $styleImg = $this->getSubject();

        $form
            ->add('imgFile', FileType::class, $this->getFormImageOptions(
                '<img src="/' . $styleImg->img
                . '" class="admin-style-img-preview" style="max-height: 200px; max-width: 600px;" />',
                'Изображение',
            ))
            ->add('imgColorFile', FileType::class, $this->getFormImageOptions(
                '<img src="/' . $styleImg->imgColor
                . '" class="admin-style-img-color-preview" style="max-height: 100px; max-width: 100px;" />',
                'Гамма',
            ))
            ->add('seq', TextType::class, ['label' => 'Последовательность'])
        ;
    }
}
