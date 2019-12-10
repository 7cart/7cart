<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Controller\Api\V1\Authenticated\TokenAuthenticatedInterface;
use Symfony\Component\Security\Core\Security;

class TokenAuthenticatedListener implements EventSubscriberInterface
{

    private $_security;

     public function __construct(Security $security)
     {
         $this->_security = $security;
     }

    public function onKernelController(ControllerEvent $event)
    {

        $controller = $event->getController();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof TokenAuthenticatedInterface) {
            if (false === $this->_security->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw new AccessDeniedException();
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}