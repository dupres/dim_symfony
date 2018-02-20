<?php

namespace AppBundle\Controller\Api;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Show;

/**
 * @Route(name="api_show_")
 */
class ShowController extends Controller
{

    /**
     * @Method({"GET"})
     * @Route("/shows",name="list")
     */
    public function listAction(SerializerInterface $serializer){

        $shows = $this->getDoctrine()->getRepository('AppBundle:Show')->findAll();

        $serializationContext = new SerializationContext();

        $data = $serializer->serialize($shows, 'json',$serializationContext->setGroups(['show']));

        return new Response($data,Response::HTTP_OK,['Content-Type'=>'application\json']);
    }

    /**
     * @Method({"GET"})
     * @Route("/shows/{id}",name="get")
     */
    public function singleAction(Show $show, SerializerInterface $serializer){

        $json = $serializer->serialize($show, 'json');

        return new Response($json,Response::HTTP_OK,['Content-Type'=>'application\json']);
    }

}