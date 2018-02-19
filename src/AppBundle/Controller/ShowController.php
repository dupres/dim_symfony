<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Show;
use AppBundle\Entity\Category;
use AppBundle\Type\ShowType;
use AppBundle\Type\CategoryType;
use AppBundle\File\FileUploader;
use AppBundle\Search\ShowSearch;
use AppBundle\ShowFinder\ShowFinder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

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
    public function listAction(Request $request, ShowFinder $showFinder)
    {
        $showRepository = $this->getDoctrine()->getRepository('AppBundle:Show');
        $session = $request->getSession();
        if ($session->has('query_search_shows')){
            $res = $showFinder->searchByName($session->get('query_search_shows'));
            $shows=$res;

            $session->remove('query_search_shows');
        }else{
            $em = $this->getDoctrine()->getManager();
            $shows = $em->getRepository('AppBundle:Show')->findAll();
            $show_api = null;
        }


        /*
        $showRepository = $this->getDoctrine()->getRepository('AppBundle:Show');
        $session = $request->getSession();
        if ($session->has('query_search_shows')){
            $querySearchShows = $session->get('query_search_shows');
            $shows = $showRepository->findAllByQuery($querySearchShows);
            $request->getSession()->remove('query_search_shows');
        }else {
            $shows = $showRepository->findAll();
            //$em = $this->getDoctrine()->getManager();
            //$shows = $em->getRepository("AppBundle:Show")->findAll();
            //return $this->render(
            //    'show/list.html.twig',
            //    [
            //        'shows' => $shows
            //    ]
            //);
        }*/
        return $this->render('show/list.html.twig',['shows'=>$shows]);
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
     * @Route("/update/{id}", name="update")
     */
    public function updateAction(Show $show, Request $request, FileUploader $fileUploader){
        $showForm = $this->createForm(ShowType::class, $show, ['validation_groups'=>['update']]);

        $showForm->handleRequest($request);

        if ($showForm->isValid()){

            if ($show->getTmpPicture()!=null){
                unlink($this->getParameter('kernel.project_dir').'/web'.$this->getParameter('upload_directory_file').'/'.$show->getMainPicture());
                $generatedFileName = $fileUploader->upload($show->getTmpPicture(), $show->getCategory()->getName());


                $show->setMainPicture($generatedFileName);
                $show->setDataSource(Show::DATA_SOURCE_DB);
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
     * @Method({"POST"})
     */
    public function searchAction(Request $request)
    {
        $request->getSession()->set('query_search_shows', $request->request->get('query'));
        return $this->redirectToRoute('show_list');
    }


    /**
     * @Route("/delete",name="delete")
     * @Method({"POST"})
     */
    public function delete_action(Request $request, CsrfTokenManagerInterface $csrfTokenManager){
        $showId = $request->request->get('show_id');

        $show=$this->getDoctrine()->getRepository('AppBundle:Show')->findOneById($showId);

        if(!$show){
            throw new NotFoundHttpException(sprintf('There is no show with ID %d',$showId));
        }

        $csrfToken = new CsrfToken('delete_show',$request->get('_csrf_token'));

        if ($csrfTokenManager->isTokenValid($csrfToken)){
            $em = $this->getDoctrine()->getManager();
            $em->remove($show);
            $em->flush();

            $this->addFlash("success","La série a bien été supprimée !");

        }else{
            $this->addFlash('danger',"Le token est invalide. La délétion n'a pas pu être effectuée.");
        }


        return $this->redirectToRoute('show_list');
    }

    /**
     * @Route("/login",name="login")
     * @Method({"POST"})
     */

}