<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\PropertySet;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PropertySetAdminController extends CRUDController
{
    private const string COPY_NAME_SUFFIX = ' (копия)';

    public function cloneAction(string $id): RedirectResponse {
        $propertySet = $this->admin->getSubject();

        if (!($propertySet instanceof PropertySet)) {
            throw new NotFoundHttpException('Unable to find the object with id: ' . $id);
        }

        try {
            $clonedPropertySet = clone($propertySet, ['name' => $propertySet->name . self::COPY_NAME_SUFFIX]);
            $this->admin->create($clonedPropertySet);
        } catch (\Throwable $e) {
            throw new \RuntimeException(
                'Unable to clone propertySet with id ' . $id . ': ' . $e->getMessage(),
                $e->getCode(),
                $e,
            );
        }

        $this->addFlash('sonata_flash_success', 'Копия создана успешно');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
