<?php

declare(strict_types=1);

namespace App\Admin;

use Symfony\Component\Validator\Constraints\Image;

trait CommonAdmin
{
    private function getFormImageOptions(string $imageHtml, string $label, bool $required = false): array {
        return [
            'help' => $imageHtml,
            'help_html' => true,
            'mapped' => false,
            'required' => $required,
            'label' => $label,
            'constraints' => [new Image(maxSize: '2M')],
        ];
    }
}
