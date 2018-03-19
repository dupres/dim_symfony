<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Category;
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
 * @Route(name="api_category_")
 */
class CategoryController extends Controller{

    /**
     * @Method({"GET"})
     * @Route("/categories",name="list")
     * @param SerializerInterface $serializer
     * @return Response
     *
     * @SWG\Response(response=200,description="Categories found")
     * @SWG\Tag(name="Categories")
     */
    public function listAction(SerializerInterface $serializer)
    {

        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $data = $serializer->serialize($categories, 'json');

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application\json']);
    }

    /**
     * @Method({"GET"})
     * @Route("/category/{id}",name="get")
     * @param int $categoryID
     * @param SerializerInterface $serializer
     * @return Response
     *
     * @SWG\Parameter(name="CategoryID",in="query",type="integer",description="The category to find")
     * @SWG\Response(response=200,description="Category created")
     * @SWG\Response(response=400,description="Category not found")
     * @SWG\Tag(name="Categories")
     */
    public function singleAction(int $categoryID, SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->find(Category::class, $categoryID);
        if (!(is_null($category))) {
            $json = $serializer->serialize($category, 'json');
            return new Response($json, Response::HTTP_OK, ['Content-Type' => 'application\json']);
        } else
            return new Response("Catégorie inconnue", Response::HTTP_NOT_FOUND, ['Content-Type' => 'application\json']);
    }

    /**
     * @Method({"PUT"})
     * @Route("/category/create",name="create")
     * @param Request $request
     * @param EncoderFactoryInterface $encoderFactory
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return String
     *
     * @SWG\Parameter(name="Category",in="body",description="Category to create",
     *     @SWG\Schema(type="array",@Model(type=AppBundle\Entity\Category::class)))
     * @SWG\Response(response=200,description="Category created")
     * @SWG\Response(response=400,description="Error, category not created")
     * @SWG\Tag(name="Categories")
     */
    public function createAction(Request $request, EncoderFactoryInterface $encoderFactory, SerializerInterface $serializer, ValidatorInterface $validator){
        $serializationContext = DeserializationContext::create();
        $category = $serializer->deserialize($request->getContent(),Category::class,'json',$serializationContext->setGroups(['category_create','category']));

        $constraintValidationList = $validator->validate($category);

        if ($constraintValidationList->count()==0){

            $encoder = $encoderFactory->getEncoder($category);
            $category->setPassword($encoder->encodePassword($category->getPassword(),null));

            $category->setRoles(explode(',',$category->getRoles()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return new Response("L'utilisateur a bien été créé !", Response::HTTP_CREATED, ['Content-Type' => 'application\json']);
        }

        return new Response("Une erreur est survenue, l'utilisateur n'a pas été créé.", Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application\json']);
    }

    /**
     * @Method({"POST"})
     * @Route("/category/update",name="update")
     *
     * @param Request $request
     * @param EncoderFactoryInterface $encoderFactoryInterface
     * @param Category $category
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return Response
     *
     * @SWG\Parameter(name="Category",in="body",description="Category to update",
     *     @SWG\Schema(type="array",@Model(type=AppBundle\Entity\Category::class)))
     * @SWG\Response(response=200,description="Category updated")
     * @SWG\Response(response=400,description="Error, category not updated")
     * @SWG\Tag(name="Categories")
     */
    public function updateAction(Category $category, Request $request, EncoderFactoryInterface $encoderFactoryInterface, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $serializationContext = DeserializationContext::create();
        $data = $serializer->deserialize($request->getContent(), Category::class, 'json', $serializationContext->setGroups(['show', 'category_create']));
        $error = $validator->validate($data);
        if ($error->count() == 0) {
            $encoder = $encoderFactoryInterface->getEncoder($data);
            $password = $encoder->encodePassword($data->getPassword(), null);
            $data->setPassword($password);
            $data->setRoles(explode(',', trim(" ", $data->getRoles())));
            $category->update($data);
            $this->getDoctrine()->getManager()->flush();
            return new Response("La catégorie a bien été mise à jour !", Response::HTTP_CREATED, ['Content-Type' => 'application\json']);
        } else {

            return new Response("Erreur :" . $serializer->serialize($error, 'json'), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application\json']);
        }
    }

    /**
     * @Method({"DELETE"})
     * @Route("/category/delete/{id}",name="delete")
     *
     * @param int $categoryID
     * @return Response
     *
     * @SWG\Parameter(name="CategoryID",in="query",type="integer",description="The category to delete")
     * @SWG\Response(response=200,description="Category deleted")
     * @SWG\Response(response=404,description="Category not found")
     * @SWG\Response(response=400,description="Missing parameter ID")
     * @SWG\Tag(name="Categories")
     */
    public function deleteAction(int $categoryID)
    {
        if(!(is_null($categoryID))){

            $em = $this->getDoctrine()->getManager();
            $category = $em->find(Category::class,$categoryID);
            if (!(is_null($category))) {
                $em->remove($category);
                $em->flush();
                return new Response("Catégorie supprimée !", Response::HTTP_OK, ['Content-Type' => 'application\json']);
            }else{
                return new Response("La catégorie semble ne pas exister", Response::HTTP_NOT_FOUND, ['Content-Type' => 'application\json']);
            }
        }else{
            return new Response("L'identifiant n'est pas valide", Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application\json']);
        }
    }
    
    
}