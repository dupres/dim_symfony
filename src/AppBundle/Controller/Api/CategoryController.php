<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{

    /**
     * @Method({"GET"})
     * @Route("/categories",name="api_category_list")
     */
    public function listAction(){

        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        return $this->json($categories);
    }

}