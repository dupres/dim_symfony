<?php

namespace AppBundle\ShowFinder;

use AppBundle\Entity\Show;

class ShowFinder
{
    private $finders;

    public function addFinder(ShowFinderInterface $finder){
        $this->finders[] = $finder;
    }


    public function searchByName($query){
        $results = [];

        foreach($this->finders as $finder) {
            $results = array_merge($results, $finder->findByName($query));
        }

        return $results;
    }
}