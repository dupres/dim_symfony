<?php

NameSpace AppBundle\Controller\Api;

use AppBundle\Entity\Media;
use AppBundle\File\FileUploader;
use GuzzleHttp\Psr7\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/media",name="media_")
 */
class MediaController extends Controller{

    /**
     * @Method({"POST"})
     * @Route("/")
     *
     * @SWG\Response(response=200,description="Media found")
     * @SWG\Tag(name="Media")
     */
    public function uploadAction(Request $request, FileUploader $fileUploader, RouterInterface $router){


        $media = new Media();

        $media->setFile($request->files->get('file'));

        // Validate media object

        $form = $this->createForm(MediaType::class,$media);

        $form->handleRequest($request);

        //if ($form->isValid()){


            $generatedFileName = $fileUploader->upload($media->getFile(),time());

            $path = $this->getParameter('upload_directory_file').'/'.$generatedFileName;

            $baseUrl = $router->getContext()->getScheme().'://'.$router->getContext()->getHost();

            $media->setPath($baseUrl.$path);
            //$media->setPath($request->getBaseUrl().$path);

            $em = $this->getDoctrine()->getManager();
            $em->persist($media);
            $em->flush();


            return $this->returnResponse('Le media a bien été créé !',Response::HTTP_CREATED);
        //}

        return $this->returnResponse('',Response::HTTP_OK);
    }

}