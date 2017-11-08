<?php

namespace PCBuild\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        return $this->render('PCBuildMainBundle:Base:index.html.twig', array(
            'login_registration_form' => $this->CreateLoginForm(),
        ));
    }
}
