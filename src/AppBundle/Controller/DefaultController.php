<?php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * @Route("/api", schemes={"http", "https"})
 * @Method({"GET"})
 */
class DefaultController extends Controller
{
    /**
     * @Route(
     *     "/{username}",
     *     requirements={"username"=".*"},
     *     name="homepage"
     * )
     */
    public function indexAction(Request $request, $username="")
    {
        // replace this example code with whatever you need
        //return $this->render('default/index.html.twig', [
        //    'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        //]);
        // dump($request->query); // vardump de Symfony
        return new Response(
            $this->renderView('default/index.html.twig', [
                'username' => $username
            ]),
            Response::HTTP_NOT_FOUND
        );
    }
}