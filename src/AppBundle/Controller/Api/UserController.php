<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(name="api_user_")
 */
class UserController extends Controller
{

    /**
     * @Method({"GET"})
     * @Route("/users",name="list")
     * @param SerializerInterface $serializer
     * @return Response
     *
     * @SWG\Response(response=200,description="Users found")
     * @SWG\Tag(name="Users")
     */
    public function listAction(SerializerInterface $serializer){

        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        $serializationContext = new SerializationContext();

        $data = $serializer->serialize($users,'json', $serializationContext->setGroups(['user']));

        return new Response($data, Response::HTTP_OK,['Content-Type'=>'application\json']);
    }

    /**
     * @Method({"GET"})
     * @Route("/user/{id}",name="get")
     * @param Integer $userID
     * @param SerializerInterface $serializer
     * @return Response
     *
     * @SWG\Parameter(name="UserID",in="query",type="integer",description="The user to find")
     * @SWG\Response(response=200,description="User created")
     * @SWG\Response(response=400,description="User not found")
     * @SWG\Tag(name="Users")
     */
    public function singleAction(int $userID, SerializerInterface $serializer){
        $em = $this->getDoctrine()->getManager();
        $user = $em->find(User::class,$userID);
        if (!(is_null($user))) {
            $json = $serializer->serialize($user, 'json');
            return new Response($json, Response::HTTP_OK, ['Content-Type' => 'application\json']);
        }else
            return new Response("Utilisateur inconnu", Response::HTTP_NOT_FOUND,['Content-Type'=>'application\json']);
    }

    /**
     * @Method({"PUT"})
     * @Route("/user/create",name="create")
     * @param Request $request
     * @param EncoderFactoryInterface $encoderFactory
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return String
     *
     * @SWG\Parameter(name="User",in="body",description="User to create",
     *     @SWG\Schema(type="array",@Model(type=AppBundle\Entity\User::class)))
     * @SWG\Response(response=200,description="User created")
     * @SWG\Response(response=400,description="Error, user not created")
     * @SWG\Tag(name="Users")
     */
    public function createAction(Request $request, EncoderFactoryInterface $encoderFactory, SerializerInterface $serializer, ValidatorInterface $validator){
        $serializationContext = DeserializationContext::create();
        $user = $serializer->deserialize($request->getContent(),User::class,'json',$serializationContext->setGroups(['user_create','user']));

        $constraintValidationList = $validator->validate($user);

        if ($constraintValidationList->count()==0){

            $encoder = $encoderFactory->getEncoder($user);
            $user->setPassword($encoder->encodePassword($user->getPassword(),null));

            $user->setRoles(explode(',',$user->getRoles()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return new Response("L'utilisateur a bien été créé !", Response::HTTP_CREATED, ['Content-Type' => 'application\json']);
        }

        return new Response("Une erreur est survenue, l'utilisateur n'a pas été créé.", Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application\json']);
    }

    /**
     * @Method({"POST"})
     * @Route("/user/update",name="update")
     *
     * @param Request $request
     * @param EncoderFactoryInterface $encoderFactoryInterface
     * @param User $user
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return Response
     *
     * @SWG\Parameter(name="User",in="body",description="User to update",
     *     @SWG\Schema(type="array",@Model(type=AppBundle\Entity\User::class)))
     * @SWG\Response(response=200,description="User updated")
     * @SWG\Response(response=400,description="Error, user not updated")
     * @SWG\Tag(name="Users")
     */
    public function updateAction(User $user, Request $request, EncoderFactoryInterface $encoderFactoryInterface, SerializerInterface $serializer, ValidatorInterface $validator){
        $serializationContext = DeserializationContext::create();
        $data = $serializer->deserialize($request->getContent(), User::class, 'json', $serializationContext->setGroups(['show','user_create']));
        $error = $validator->validate($data);
        if($error->count() == 0){
            $encoder = $encoderFactoryInterface->getEncoder($data);
            $password = $encoder->encodePassword($data->getPassword(), null);
            $data->setPassword($password);
            $data->setRoles(explode(',', trim(" ",$data->getRoles())));
            $user->update($data);
            $this->getDoctrine()->getManager()->flush();
            return new Response("L'utilisateur a bien été mis à jour !", Response::HTTP_CREATED, ['Content-Type' => 'application\json']);
        }else{

            return new Response("Erreur :".$serializer->serialize($error, 'json'), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application\json']);
        }
    }

    /**
     * @Method({"DELETE"})
     * @Route("/user/delete/{id}",name="delete")
     *
     * @param int $userID
     * @return Response
     *
     * @SWG\Parameter(name="UserID",in="query",type="integer",description="The user to delete")
     * @SWG\Response(response=200,description="User deleted")
     * @SWG\Response(response=404,description="User not found")
     * @SWG\Response(response=400,description="Missing parameter ID")
     * @SWG\Tag(name="Users")
     */
    public function deleteAction(int $userID)
    {
        if(!(is_null($userID))){

            $em = $this->getDoctrine()->getManager();
            $user = $em->find(User::class,$userID);
            if (!(is_null($user))) {
                $em->remove($user);
                $em->flush();
                return new Response("Utilisateur supprimé !", Response::HTTP_OK, ['Content-Type' => 'application\json']);
            }else{
                return new Response("L'utilisateur semble ne pas exister", Response::HTTP_NOT_FOUND, ['Content-Type' => 'application\json']);
            }
        }else{
            return new Response("L'identifiant n'est pas valide", Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application\json']);
        }
    }

}