<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 *
 * @UniqueEntity("name", message="{{ value }} is already used")
 *
 * @JMS\ExclusionPolicy("all");
 */
class Category
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


}
