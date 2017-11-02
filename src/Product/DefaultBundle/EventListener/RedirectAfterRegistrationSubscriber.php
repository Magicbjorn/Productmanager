<?php

namespace Product\DefaultBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use FOS\UserBundle\FOSUserEvents;

class RedirectAfterRegistrationSubscriber implements EventSubscriberInterface
{
	private $router;
    
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onRegistrationSuccess($event)
    {
        $url = $this->router->generate('product_index');
        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }
    
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        ];
    }
}
