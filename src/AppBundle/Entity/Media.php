<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 *
 * @UniqueEntity("name", message="{{ value }} is already used")
 *
 * @JMS\ExclusionPolicy("all");
 */
class Media
{
    // --------------------------------
    //        Fields
    //--------------------------------

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",unique=true)
     *
     * @JMS\Expose
     */
    private $name;

    private $file;

    // --------------------------------
    //        Properties
    //--------------------------------

    public function update(Category $category){
        $this->setName($category->getName());
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getFile(){
        return $this->file;
    }

    public function setFile($file){
        $this->file = $file;
    }


}



