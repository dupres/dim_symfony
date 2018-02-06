<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Show;
use AppBundle\Type\ShowType;
use AppBundle\Type\CategoryType;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(name="show_")
 */
class ShowController extends Controller
{
    /**
     * @Route("/create",name="create")
     */
    public function createAction(Request $request)
    {
        $show = new Show();
        $form = $this->createForm(ShowType::class, $show);

        $form->handleRequest($request);

        if ($form->isValid()){

            $generatedFileName = time().'_'.$show->getCategory()->getName().$show->getMainPicture()->guessClientExtension();
            $path = $this->getParameter('kernel.project_dir').'/web'.$this->getParameter('upload_directory_file');

            $show->getMainPicture()->move($path,$generatedFileName);

            $show->setMainPicture($generatedFileName);

            //dump($show); die;
            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();


            // upload file



            // Save

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
        return $this->render('show/list.html.twig');
    }

    public function categoriesAction(){
        return $this->render('_includes/categories.html.twig',[
            'categories' => ['Web Design','HTML','Freebies','Javascript','CSS','Tutorials']]
        );
    }

}