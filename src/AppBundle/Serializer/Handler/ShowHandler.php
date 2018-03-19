<?php

namespace AppBundle\Serializer\Handler;


use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonDeserializationVisitor;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class ShowHandler implements SubscribingHandlerInterface
{
    private $doctrine;
    private $tokenStorage;

    public function __construct(ManagerRegistry $doctrine, TokenStorageInterface $tokenStorage)
    {
        $this->doctrine = $doctrine;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'AppBundle\Entity\Show',
                'method' => 'deserialize'
            ],
        ];
    }

    public function deserialize(JsonDeserializationVisitor $visitor, $data){

        $show = new Show();
        $show
            ->setName($data['name'])
            ->setAbstract($data['abstract'])
            ->setCountry($data['country'])
            ->setReleaseDate(new \DateTime($data['release_date']))
            ->setMainPicture($data['main_picture'])
        ;

        $em = $this->doctrine->getManager();

        if (!$category = $em->getRepository('AppBundle:Category')->findOneBy($data['category']['id'])){
            throw new \LogicException('The category does not exists');
        }

        $user = $this->tokenStorage->getToken()->getUser();

        $show->setCategory($category);
        $show->setAuthor($user);

        return $show;


    }


}