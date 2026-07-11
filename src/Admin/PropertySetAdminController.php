<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\PropertySet;
use App\Service\PropertySetManager;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PropertySetAdminController extends CRUDController
{
    public function __construct(
        private readonly PropertySetManager $propertySetManager,
    ) {}

    public function cloneAction(string $id): RedirectResponse {
        /** @var PropertySet $propertySet */
        $propertySet = $this->admin->getSubject();

        $clonedPropertySet = $this->propertySetManager->getCopy($propertySet);
        $this->admin->create($clonedPropertySet);

        $this->addFlash('sonata_flash_success', 'Копия создана успешно');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
