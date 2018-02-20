<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Category;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(name="api_category_")
 */
class CategoryController extends Controller
{

    /**
     * @Method({"GET"})
     * @Route("/categories",name="list")
     */
    public function listAction(SerializerInterface $serializer){

        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $data = $serializer->serialize($categories,'json');

        return new Response($data, Response::HTTP_OK,['Content-Type'=>'application\json']);
    }

    /**
     * @Method({"GET"})
     * @Route("/categories/{id}",name="get")
     */
    public function singleAction(Category $category, SerializerInterface $serializer){

        $json = $serializer->serialize($category,'json');

        return new Response($json, Response::HTTP_OK,['Content-Type'=>'application\json']);
    }

    /**
     * @Method({"POST"})
     * @Route("/categories", name="create")
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator){
        $category = $serializer->deserialize($request->getContent(),Category::class,'json');

        $constraintValidationList = $validator->validate($category);

        if ($constraintValidationList->count()==0){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->returnResponse('Categorie créée',Response::HTTP_CREATED);
        }

        return $this->returnResponse('Problème lors de la création de catégorie',Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Method({"PUT"})
     * @Route("/categories/{id}", name="update")
     */
    public function updateAction(Category $category, Request $request,  SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $newCategory = $serializer->deserialize($request->getContent(), Category::class, 'json');
        $constraintValidationList = $validator->validate($newCategory);

        if ($constraintValidationList->count() == 0) {
            $category->update($newCategory);
            $em = $this->getDoctrine()->getManager()->flush();

            return $this->returnResponse('Categorie mise à jour', Response::HTTP_CREATED);
        }

        return $this->returnResponse('Problème lors de la modification de catégorie', Response::HTTP_BAD_REQUEST);
    }
}