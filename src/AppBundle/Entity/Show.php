<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Search\ShowSearch")
 * @ORM\Table(name="s_show")
 */
class Show{

    // --------------------------------
    //        Fields
    //--------------------------------

    const DATA_SOURCE_OMDB = "OMDB";
    const DATA_SOURCE_DB = "In local database";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $abstract;

    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="shows")
     * @ORM\JoinColumn(name="show_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @ORM\Column(type="date")
     */
    private $releasedDate;

    /**
     * @ORM\Column(type="string")
     * @Assert\Image(minHeight=300, minWidth=750)
     */
    private $mainPicture;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id",referencedColumnName="id")
     *
     */
    private $category;

    private $tmpPicture;

    /**
     * @ORM\Column(options={"default":"In local database"})
     */
    private $dataSource;


    // --------------------------------
    //        Fields
    //--------------------------------

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     * @Assert\NotBlank(message="Please provide a name for the show.", groups={"create"})
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     * @Assert\NotBlank(message="Please provide a name for the show.", groups={"create"})
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * @param mixed $abstract
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
        return $this;
    }

    /**
     * @return mixed
     * @Assert\NotBlank(groups={"create"})
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     * @Assert\NotBlank(groups={"create"})
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return mixed
     * @Assert\NotBlank(groups={"create"})
     */
    public function getReleasedDate()
    {
        return $this->releasedDate;
    }

    /**
     * @param mixed $releasedDate
     */
    public function setReleasedDate($releasedDate)
    {
        $this->releasedDate = $releasedDate;
        return $this;
    }

    /**
     * @return mixed
     * @Assert\NotBlank(groups={"create"})
     */
    public function getMainPicture()
    {
        return $this->mainPicture;
    }

    /**
     * @return mixed
     * @Assert\NotBlank(groups={"create"})
     */
    public function getTmpPicture()
    {
        return $this->tmpPicture;
    }

    /**
     * @param mixed $tmpPicture
     */
    public function setTmpPicture($tmpPicture)
    {
        $this->tmpPicture = $tmpPicture;
        return $this;
    }

    /**
     * @param mixed $mainPicture
     */
    public function setMainPicture($mainPicture)
    {
        $this->mainPicture = $mainPicture;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    public function getDataSource(){
        return $this->dataSource;
    }
    public function setDataSource($dataSource){
        $this->dataSource = $dataSource;
        return $this;
    }


}