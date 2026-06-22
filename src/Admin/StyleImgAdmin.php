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
    protected function configureFormFields(FormMapper $form): void {
        /** @var StyleImg $styleImg */
        $styleImg = $this->getSubject();

        $imgHtml = '<img src="/' . $styleImg->img . '" class="admin-style-img-preview" '
            . 'style="max-height: 200px; max-width: 600px;" />'
        ;
        $imgOptions = [
            'help' => $imgHtml,
            'help_html' => true,
            'required' => false,
            'label' => 'Изображение',
        ];

        $imgColorHtml = '<img src="/' . $styleImg->imgColor . '" class="admin-style-img-color-preview" '
            . 'style="max-height: 100px; max-width: 100px;" />'
        ;
        $imgColorOptions = [
            'help' => $imgColorHtml,
            'help_html' => true,
            'required' => false,
            'label' => 'Гамма',
        ];

        $form
            ->add('imgFile', FileType::class, $imgOptions)
            ->add('imgColorFile', FileType::class, $imgColorOptions)
            ->add('seq', TextType::class, ['label' => 'Последовательность'])
        ;
    }
}
