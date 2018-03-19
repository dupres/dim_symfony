<?php

namespace AppBundle\Search;

use AppBundle\Entity\Show;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Tests\Fixtures\Entity;

class ShowSearch extends EntityRepository
{
    public function findAllByQuery($query)
    {
        return $this->createQueryBuilder('s')
            ->where('LOWER(s.name) LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult()
            ;
    }
}