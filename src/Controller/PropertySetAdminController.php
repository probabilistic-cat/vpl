<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PropertySet;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PropertySetAdminController extends CRUDController
{
    public function cloneAction(string $id): RedirectResponse {
        /** @var PropertySet $propertySet */
        $propertySet = $this->admin->getSubject();

        if (!$propertySet) {
            throw new NotFoundHttpException(sprintf('Unable to find the object with id: %s', $id));
        }

        $clonedPropertySet = clone $propertySet;
        $clonedPropertySet->setName($propertySet->getName() . ' (копия)');
        $this->admin->create($clonedPropertySet);

        $this->addFlash('sonata_flash_success', 'Копия создана успешно');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
