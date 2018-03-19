<?php

namespace AppBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Search\ShowSearch")
 * @ORM\Table(name="s_show")
 *
 * @JMS\ExclusionPolicy("all")
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
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=100)
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $abstract;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="shows")
     * @ORM\JoinColumn(name="show_id", referencedColumnName="id")
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id",referencedColumnName="id")
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $category;

    /**
     * @ORM\Column(type="text")
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $country;

    /**
     * @ORM\Column(options={"default":"In local database"})
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $dataSource;

    /**
     * @ORM\Column(type="string")
     * @Assert\Image(minHeight=300, minWidth=750)
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $mainPicture;

    /**
     * @ORM\Column(type="date")
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $releasedDate;

    private $tmpPicture;


    // --------------------------------
    //        Properties
    //--------------------------------

    public function update(Show $show){
        $this->setName($show->getName());
        $this->setAbstract($show->getAbstract());
        $this->setAuthor($show->getAuthor());
        $this->setCategory($show->getCategory());
        $this->setCountry($show->getCountry());
        $this->setMainPicture($show->getMainPicture());
        $this->setReleasedDate($show->getReleasedDate());
    }

    // --------------------------------
    //        Getters
    //--------------------------------

    /**
     * @return mixed
     * @Assert\NotBlank(message="Please provide a name for the show.", groups={"create"})
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     * @Assert\NotBlank(message="Please provide an abstract for the show.", groups={"create"})
     */
    public function getAbstract()
    {
        return $this->abstract;
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
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return mixed
     * @Assert\NotBlank(groups={"create"})
     */
    public function getCountry()
    {
        return $this->country;
    }

    public function getDataSource(){
        return $this->dataSource;
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
    public function getReleasedDate()
    {
        return $this->releasedDate;
    }

    /**
     * @return mixed
     * @Assert\NotBlank(groups={"create"})
     */
    public function getTmpPicture()
    {
        return $this->tmpPicture;
    }

    // --------------------------------
    //        Setters
    //--------------------------------

    /**
     * @return mixed
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     * @param mixed $abstract
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
        return $this;
    }

    /**
     * @return mixed
     * @param mixed $author
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return mixed
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return mixed
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function setDataSource($dataSource){
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * @return mixed
     * @param mixed $mainPicture
     */
    public function setMainPicture($mainPicture)
    {
        $this->mainPicture = $mainPicture;
        return $this;
    }

    /**
     * @return mixed
     * @param mixed $releasedDate
     */
    public function setReleasedDate($releasedDate)
    {
        $this->releasedDate = $releasedDate;
        return $this;
    }
    /**
     * @return mixed
     * @param mixed $tmpPicture
     */
    public function setTmpPicture($tmpPicture)
    {
        $this->tmpPicture = $tmpPicture;
        return $this;
    }


}