<?php

namespace AppBundle\ShowFinder;



class ShowFinder
{
    private $finders;

    public function addFinder(ShowFinderInterface $finder){
        $this->finders[] = $finder;
    }


    public function searchByName($query){
        foreach($this->finders as $finder){
            $tmp[$finder->getName()] = $finder->findByName($query);
        }

        return $tmp;
    }
}