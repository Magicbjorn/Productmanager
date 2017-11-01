<?php

namespace Product\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ProductDefaultBundle:Default:index.html.twig');
    }
}
