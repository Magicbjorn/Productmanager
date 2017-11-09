<?php

namespace PCBuild\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class BaseController extends Controller
{
    public function CreateLoginForm()
    {
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        $form        = $formFactory->createForm();
        return $form->createView();
    }

    public function indexAction(Request $request)
    {
        $error = null;
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        $authErrorKey    = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        return $this->render('PCBuildMainBundle:Base:index.html.twig', array(
            'login_registration_form' => $this->CreateLoginForm(),
            'error'                   => $error,
        ));
    }
}
