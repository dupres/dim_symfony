<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="api_user_")
 */
class UserController extends Controller
{

    /**
     * @Method({"GET"})
     * @Route("/users",name="list")
     */
    public function listAction(SerializerInterface $serializer){

        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        $serializationContext = new SerializationContext();

        $data = $serializer->serialize($users,'json', $serializationContext->setGroups(['user']));

        return new Response($data, Response::HTTP_OK,['Content-Type'=>'application\json']);
    }

    /**
     * @Method({"GET"})
     * @Route("/users/{id}",name="get")
     */
    public function singleAction(User $user, SerializerInterface $serializer){

        $json = $serializer->serialize($user,'json');

        return new Response($json, Response::HTTP_OK,['Content-Type'=>'application\json']);
    }



}