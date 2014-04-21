<?php

namespace FJ\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FJUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
