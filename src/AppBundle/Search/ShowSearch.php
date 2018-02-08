<?php

namespace AppBundle\Search;

use AppBundle\Entity\Show;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShowSearch
{


    public function __construct(){}

    /**
     * @param $name
     * @return Show[]
     */
    public function findWithName($name)
    {
    	$repository = $this->getDoctrine()->getRepository(Show::class);
    	// return $repository->findBy(['name'=>$name]);


    	// $conn = $this->getEntityManager()->getConnection();
    	// $sql = 'select * from s_show where name like "%'.$name.'%";';
    	// $stmt = $conn->prepare($sql);
    	// return $stmt->execute();

    	// http://www.thisprogrammingthing.com/2017/Finding-Things-With-Symfony-3/

        $queryBuilder = $repository->createQueryBuilder('s');
        $shows = $queryBuilder->select(array('s'))
        	->from('AppBundle:Show','s')
            ->where(" name like '%:name%'")
            ->setParameter('name',$name)
            ->getQuery()
            ->getResult();
        return $shows;
    }
}