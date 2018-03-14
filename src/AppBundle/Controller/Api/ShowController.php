<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Show;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(name="api_show_")
 */
class ShowController extends Controller
{

    /**
     * @Method({"GET"})
     * @Route("/shows",name="list")
     * @param SerializerInterface $serializer
     * @return Response
     *
     * @SWG\Response(response=200,description="Shows found")
     * @SWG\Tag(name="Shows")
     */
    public function listAction(SerializerInterface $serializer){

        $shows = $this->getDoctrine()->getRepository('AppBundle:Show')->findAll();

        $serializationContext = new SerializationContext();

        $data = $serializer->serialize($shows, 'json',$serializationContext->setGroups(['show']));

        return new Response($data,Response::HTTP_OK,['Content-Type'=>'application\json']);
    }

    /**
     * @Method({"GET"})
     * @Route("/show/{id}",name="get")
     * @param Show $showID
     * @param SerializerInterface $serializer
     * @return Response
     *
     * @SWG\Parameter(name="ShowID",in="query",type="integer",description="The Show to find")
     * @SWG\Response(response=200,description="Show created")
     * @SWG\Response(response=400,description="Show not found")
     * @SWG\Tag(name="Shows")
     */
    public function singleAction(Show $showID, SerializerInterface $serializer){
        $em = $this->getDoctrine()->getManager();
        $show = $em->find(Show::class,$showID);
        if (!(is_null($show))) {
            $json = $serializer->serialize($show, 'json');
            return new Response($json, Response::HTTP_OK, ['Content-Type' => 'application\json']);
        }else
            return new Response("Utilisateur inconnu", Response::HTTP_NOT_FOUND,['Content-Type'=>'application\json']);
    }

    
    /**
     * @Method({"PUT"})
     * @Route("/show/create",name="create")
     * @param Request $request
     * @param EncoderFactoryInterface $encoderFactory
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return String
     *
     * @SWG\Parameter(name="Show",in="body",description="Show to create",
     *     @SWG\Schema(type="array",@Model(type=AppBundle\Entity\Show::class)))
     * @SWG\Response(response=200,description="Show created")
     * @SWG\Response(response=400,description="Error, show not created")
     * @SWG\Tag(name="Shows")
     */
    public function createAction(Request $request, EncoderFactoryInterface $encoderFactory, SerializerInterface $serializer, ValidatorInterface $validator){
        $serializationContext = DeserializationContext::create();
        $show = $serializer->deserialize($request->getContent(),Show::class,'json',$serializationContext->setGroups(['show_create','show']));

        $constraintValidationList = $validator->validate($show);

        if ($constraintValidationList->count()==0){

            $encoder = $encoderFactory->getEncoder($show);
            $show->setPassword($encoder->encodePassword($show->getPassword(),null));

            $show->setRoles(explode(',',$show->getRoles()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();

            return new Response("L'utilisateur a bien été créé !", Response::HTTP_CREATED, ['Content-Type' => 'application\json']);
        }

        return new Response("Une erreur est survenue, l'utilisateur n'a pas été créé.", Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application\json']);
    }


    /**
     * @Method({"POST"})
     * @Route("/show/update",name="update")
     *
     * @param Request $request
     * @param EncoderFactoryInterface $encoderFactoryInterface
     * @param Show $show
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return Response
     *
     * @SWG\Parameter(name="Show",in="body",description="Show to update",
     *     @SWG\Schema(type="array",@Model(type=AppBundle\Entity\Show::class)))
     * @SWG\Response(response=200,description="Show updated")
     * @SWG\Response(response=400,description="Error, show not updated")
     * @SWG\Tag(name="Shows")
     */
    public function updateAction(Show $show, Request $request, EncoderFactoryInterface $encoderFactoryInterface, SerializerInterface $serializer, ValidatorInterface $validator){
        $serializationContext = DeserializationContext::create();
        $data = $serializer->deserialize($request->getContent(), Show::class, 'json', $serializationContext->setGroups(['show','show_create']));
        $error = $validator->validate($data);
        if($error->count() == 0){
            $encoder = $encoderFactoryInterface->getEncoder($data);
            $password = $encoder->encodePassword($data->getPassword(), null);
            $data->setPassword($password);
            $data->setRoles(explode(',', trim(" ",$data->getRoles())));
            $show->update($data);
            $this->getDoctrine()->getManager()->flush();
            return new Response("L'utilisateur a bien été mis à jour !", Response::HTTP_CREATED, ['Content-Type' => 'application\json']);
        }else{

            return new Response("Erreur :".$serializer->serialize($error, 'json'), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application\json']);
        }
    }

    /**
     * @Method({"DELETE"})
     * @Route("/show/delete/{id}",name="delete")
     *
     * @param int $showID
     * @return Response
     *
     * @SWG\Parameter(name="ShowID",in="query",type="integer",description="The show to delete")
     * @SWG\Response(response=200,description="Show deleted")
     * @SWG\Response(response=404,description="Show not found")
     * @SWG\Response(response=400,description="Missing parameter ID")
     * @SWG\Tag(name="Shows")
     */
    public function deleteAction(int $showID)
    {
        if(!(is_null($showID))){

            $em = $this->getDoctrine()->getManager();
            $show = $em->find(Show::class,$showID);
            if (!(is_null($show))) {
                $em->remove($show);
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