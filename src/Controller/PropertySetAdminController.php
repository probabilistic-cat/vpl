<?php

namespace App\Controller;

use App\Entity;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PropertySetAdminController extends CRUDController
{
    /**
     * @param $id
     */
    public function cloneAction($id)
    {
        $propertySet = $this->admin->getSubject();

        if (!$propertySet) {
            throw new NotFoundHttpException(sprintf('Unable to find the object with id: %s', $id));
        }

        $clonedPropertySet = clone $propertySet;
        $clonedPropertySet->setName($propertySet->getName().' (копия)');
        $this->admin->create($clonedPropertySet);

        foreach ($clonedPropertySet->getPropertyItems() as $propertyItem) {
            $propertyItem->actualizeFileName();
            $this->admin->update($propertyItem);
        }

        $this->addFlash('sonata_flash_success', 'Копия создана успешно');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
