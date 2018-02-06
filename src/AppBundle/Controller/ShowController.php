<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Show;
use AppBundle\Type\ShowType;
use AppBundle\Type\CategoryType;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\File\FileUploader;

/**
 * @Route(name="show_")
 */
class ShowController extends Controller
{
    /**
     * @Route("/create",name="create")
     */
    public function createAction(Request $request, FileUploader $fileUploader)
    {
        $show = new Show();
        $form = $this->createForm(ShowType::class, $show);

        $form->handleRequest($request);

        if ($form->isValid()){

            $generatedFileName = $fileUploader->upload($show->getTmpPicture(), $show->getCategory()->getName());

            $show->setMainPicture($generatedFileName);

            //dump($show); die;
            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();

            $this->addFlash('success','You successfully added a new show !');

            return $this->redirectToRoute('show_list');
        }

        return $this->render('show/create.html.twig',['showForm'=> $form->createView()]);

    //    return $this->render('show/create.html.twig');
    }

    /**
     * @Route("/",name="list")
     */
    public function listAction()
    {

        $em = $this->getDoctrine()->getManager();
        $shows = $em->getRepository("AppBundle:Show")->findAll();
        return $this->render(
            'show/list.html.twig',
            [
                'shows' => $shows
            ]
        );
    }

    public function categoriesAction(){
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository("AppBundle:Category")->findAll();
        return $this->render(
            "_includes/categories.html.twig",
            [
                'categories' => $categories
            ]
        );
    }

    /**
     * @Route @Route("/update/{id}", name="update")
     */
    public function updateAction(Show $show, Request $request, FileUploader $fileUploader){
        $showForm = $this->createForm(ShowType::class, $show, ['validation_groups'=>['update']]);

        $showForm->handleRequest($request);

        if ($showForm->isValid()){

            if ($show->getTmpPicture()!=null){
                $generatedFileName = $fileUploader->upload($show->getTmpPicture(), $show->getCategory()->getName());

                $show->setMainPicture($generatedFileName);
            }

            //dump($show); die;
            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();


            $this->addFlash('success','You successfully updated the show !');

            return $this->redirectToRoute('show_list');
        }

        return $this->render('show/create.html.twig',['showForm'=>$showForm->createView()]);
    }


}