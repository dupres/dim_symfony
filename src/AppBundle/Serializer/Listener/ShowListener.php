<?php

namespace AppBundle\Serializer\Listener;

use AppBundle\Entity\Show;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class ShowListener implements EventSubscriberInterface
{
    private $doctrine;
    private $tokenStorage;

    public function __construct(ManagerRegistry $doctrine, TokenStorageInterface $tokenStorage)
    {
        $this->doctrine = $doctrine;
        $this->tokenStorage = $tokenStorage;
    }


    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => Events::PRE_DESERIALIZE,
                'method' => 'preDeserialize',
                'class' => 'AppBundle\\Entity\\Show',
                'format' => 'json'
            ],
        ];
    }

    public function preDeserialize(ObjectEvent $event){

        // $event->setData(['dataSource'=>Show::DATA_SOURCE_DB]);

    }

}