<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route(name="show_")
 */
class ShowController extends Controller
{
    /**
     * @Route("/",name="list")
     */
    public function listAction()
    {
        return $this->render('show/list.html.twig');
    }
}