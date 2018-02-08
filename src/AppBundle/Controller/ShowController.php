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
use AppBundle\Search\ShowSearch;

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
                unlink($this->getParameter('kernel.project_dir').'/web'.$this->getParameter('upload_directory_file').'/'.$show->getMainPicture());
                $generatedFileName = $fileUploader->upload($show->getTmpPicture(), $show->getCategory()->getName());

                $show->setMainPicture($generatedFileName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();
            $this->addFlash('success','You successfully updated the show!');

            return $this->redirectToRoute('show_list');
        }

        return $this->render('show/Create.html.twig', array('showForm' => $showForm->createView(),'show' => $show));
    }


    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {
        $name = $request->request->get('search');

        $search = new ShowSearch();
        $shows = $search->findWithName($name);

        return $this->render(
            'show/list.html.twig',
            [
                'shows' => $shows,
                'search' => $name
            ]
        );

        // $em = $this->getDoctrine()->getManager();
        // $metadata = new ClassMetadata("Show");
        // $entityRepository = new EntityRepository($em,$metadata);
        // $queryBuilder = $entityRepository->createQueryBuilder('s');
        // $shows = $queryBuilder->select(array('s'))
        //     ->from('AppBundle:show','s')
        //     ->where(" name like '%:name%'")
        //     ->setParameter('name',$name)
        //     ->getQuery()
        //     ->getResult();


        // $conn = $this->getEntityManager()->getConnection();
        // $sql = 'select * from s_show where name like "%'.$name.'%";';
        // $stmt = $conn->prepare($sql);
        // return $stmt->execute();

        //http://www.thisprogrammingthing.com/2017/Finding-Things-With-Symfony-3/

        // $queryBuilder = $this->createQueryBuilder('s');
        // $shows = $queryBuilder->select(array('s'))
        //  ->from('AppBundle:show','s')
        //     ->where(" name like '%:name%'")
        //     ->setParameter('name',$name)
        //     ->getQuery()
        //     ->getResult();
        // return $shows;


    }

}