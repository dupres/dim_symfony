<?php

namespace AppBundle\ShowFinder;


class ShowFinder
{
    private $finders;

    public function addFinder(ShowFinderInterface $finder){
        $this->finders[] = $finder;
    }


    public function searchByName($query){

    }
}