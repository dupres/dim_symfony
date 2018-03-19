<?php

namespace AppBundle\Listener;


use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionListener implements EventSubscriberInterface
{

    const EXCEPTION_CODE = "An unknown bug has been encountered";

    public static function getSubscribedEvents(){
        return [
            KernelEvents::EXCEPTION => ['processExceptionForApi',0]
        ];
    }

    public static function processExceptionForApi(GetResponseForExceptionEvent $event){

        $request = $event->getRequest();
        $routeName =  $request->attributes->get('_route');

        $api = substr($routeName,0,3);
        if ($api !== 'api'){
            return ;
        }

        $data = [
            'code' => self::EXCEPTION_CODE,
            'message' => $event->getException()->getMessage()
        ];

        $response = new JsonResponse($data,Response::HTTP_INTERNAL_SERVER_ERROR);
        $event->setResponse($response);

    }


}