<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Type\UserType;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/user",name="user_")
 */
class UserController extends Controller{
    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @param EncoderFactoryInterface $encoderFactory
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request, EncoderFactoryInterface $encoderFactory){

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Accès refusé');

        $user = new User();
        $userForm = $this->createForm(UserType::class,$user);

        $userForm->handleRequest($request);

        if ($userForm->isValid()){
            $em = $this->getDoctrine()->getManager();

            $encoder = $encoderFactory->getEncoder($user);
            $hashedPassword=$encoder->encodePassword($user->getPassword(),null);

            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success',"L'utilisateur a bien été ajouté !");

            return $this->redirectToRoute('show_list');
        }

        return $this->render("user/create.html.twig",['userForm'=>$userForm->createView()]);
    }





    /**
     * @Route("/list",name="list")
     */
    public function listAction(){
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Accès refusé');

        return $this->render('user/list.html.twig',['users'=>$this->getDoctrine()->getRepository('AppBundle:User')->findAll()]);
    }


}