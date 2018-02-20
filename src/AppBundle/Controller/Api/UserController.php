<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\DeserializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

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

    /**
     * @Method({"POST"})
     * @Route("/users",name="create")
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator){
        $serializationContext = DeserializationContext::create();
        $user = $serializer->deserialize($request->getContent(),User::class,'json',$serializationContext->setGroups(['user_create','user']));

        dump($user); die;

        $constraintValidationList = $validator->validate($user);

        if ($constraintValidationList->count()==0){

            $encoder = $encoderFactory->getEncoder($user);
            $user->setPassword($encoder->encodePassword($user->getPassword(),null));

            $user->setRoles(explode(',',$user->getRoles()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->returnResponse('Utilisateur créé',Response::HTTP_CREATED);
        }

        return $this->returnResponse('Problème lors de la création de l\'utilisateur',Response::HTTP_BAD_REQUEST);


    }

}