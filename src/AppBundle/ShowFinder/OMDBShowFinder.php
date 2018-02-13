<?php

namespace AppBundle\ShowFinder;

use GuzzleHttp\Client;

class OMDBShowFinder implements ShowFinderInterface
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function findByName($query){
        $results = $this->client->get('/?apikey=9bcaa22f&type=series&t="'.$query.'"');

        return \GuzzleHttp\json_decode($results->getBody(),true);
    }

    public function getName(){
        return 'IMDB API';
    }
}