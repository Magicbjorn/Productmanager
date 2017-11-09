<?php

namespace PCBuild\MainBundle\EventListener;

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
        dump($event);
        $url = $this->router->generate('index');
        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }

    public function onRegistrationFailure($event)
    {
        $request = $event->getRequest();
        $form = $event->getForm();

        $errors = $form->getErrors($form);

        $flashbag = $request->getSession()->getFlashBag();

        foreach ($errors as $message) {
            $flashbag->add('failure', $message->getMessage());
        }

        $url = $this->router->generate('index');
        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }
    
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_FAILURE => 'onRegistrationFailure'
        ];
    }
}
