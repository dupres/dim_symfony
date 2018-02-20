<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table
 * @UniqueEntity("email")
 */
class User implements UserInterface
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
     * @ORM\Column(type="string",length=255)
     */
    private $fullname;

    /**
     * @ORM\Column
     */
    private $password;

    /**
     * @ORM\Column
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="Show", mappedBy="author")
     */
    private $shows;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles;


    // --------------------------------
    //        Properties
    //--------------------------------

    public function __construct() {
        $this->shows = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }


    public function getFullname()
    {
        return $this->fullname;
    }

    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    }



    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password){
        $this->password=$password;
    }



    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles){
        $this->roles = $roles;
    }

    public function addRole($role){
        $this->roles.add($role);
    }



    public function getSalt()
    {}



    public function getUsername()
    {
        return $this->email;
    }

    public function setUsername($email){
        $this->email=$email;
    }



    public function getEmail(){
        return $this->email;
    }

    public function setEmail(){

    }



    public function eraseCredentials()
    {}



    public function addShow(Show $show){
        if (!($this->shows->contains($show)))
            $this->shows->add($show);
    }

    public function removeShow(Show $show){
        $this->shows->remove($show);
    }

    public function getShows(){
        return $this->shows;
    }

}