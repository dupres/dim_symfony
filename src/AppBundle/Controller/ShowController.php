<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Show;
use AppBundle\Type\ShowType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(name="show_")
 */
class ShowController extends Controller
{
    /**
     * @Route("/",name="list")
     */
    public function listAction()
    {
        return $this->render('show/list.html.twig');
    }

    /**
     * @Route("/create",name="create")
     */
    public function createAction(Request $request)
    {
        $show = new Show();
        $form = $this->createForm(ShowType::class, $show);

        $form->handleRequest($request);

        if ($form->isValid()){
            //dump($show); die;
            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();


            // upload file



            // Save

            $this->addF('success','You successfully added a new show !');




            return $this->redirectToRoute('show_list');
        }

        return $this->render('show/create.html.twig',['showForm'=> $form->createView()]);

    //    return $this->render('show/create.html.twig');
    }

    public function categoriesAction(){
        return $this->render('_includes/categories.html.twig',[
            'categories' => ['Web Design','HTML','Freebies','Javascript','CSS','Tutorials']]
        );
    }

}