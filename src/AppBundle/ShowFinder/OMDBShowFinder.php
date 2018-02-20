<?php

namespace AppBundle\ShowFinder;

use AppBundle\Entity\Category;
use GuzzleHttp\Client;
use AppBundle\Entity\Show;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Constraints\DateTime;

class OMDBShowFinder implements ShowFinderInterface
{
    private $client;

    private $tokenStorage;

    private $apiKey;

    public function __construct(Client $client, TokenStorage $tokenStorage, $apiKey)
    {
        $this->tokenStorage = $tokenStorage;
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * @param String $query
     * @return array $shows
     */
    public function findByName($query){
        $results = $this->client->get('/?apikey=9bcaa22f&type=series&t="'.$query.'"');

        $json = \GuzzleHttp\json_decode($results->getBody(),true);

        if ($json['Response']=='False' && $json['Error']=='Series not found'){
            return [];
        }

        return $this->convertToShow($json);
    }

    /**
     * Transforms OMDB JSON result into Show and Category
     *
     * @param String $json
     *
     * Show $show
     */
    private function convertToShow($json){
        $category = new Category();
        $category->setName($json['Genre']);
        $shows = [];
        $show = new Show();

        $show->setName($json['Title'])
            ->setDataSource(Show::DATA_SOURCE_OMDB)
            ->setAbstract($json['Plot'])
            ->setCountry($json['Country'])
            //->setAuthor($json['Author'])
            ->setReleasedDate(new \DateTime($json['Released']))
            ->setMainPicture($json['Poster'])
            ->setCategory($category);

        $shows[] = $show;

        return $shows;
    }

    public function getName(){
        return 'IMDB API';
    }
}