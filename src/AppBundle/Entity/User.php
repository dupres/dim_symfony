<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table
 * @UniqueEntity("email")
 *
 * @JMS\ExclusionPolicy("all")
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
     *
     * @JMS\Groups({"user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     *
     * @JMS\Expose
     * @JMS\Groups({"user","show"})
     */
    private $fullname;

    /**
     * @ORM\Column
     *
     * @JMS\Expose
     */
    private $password;

    /**
     * @ORM\Column
     *
     * @JMS\Groups({"user"})
     * @JMS\Groups({"user_create"})
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="Show", mappedBy="author")
     *
     * @JMS\Expose
     */
    private $shows;

    /**
     * @ORM\Column(type="json_array")
     *
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\Groups({"user_create"})
     */
    private $roles;


    // --------------------------------
    //        Properties
    //--------------------------------

    public function __construct() {
        $this->shows = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function update(User $user){
        $this->fullname = $user->fullname;
        $this->password = $user->password;
        $this->roles = $user->roles;
    }


    /**
     * @return mixed
     * @Assert\NotBlank(message="Please provide a name for the User.", groups={"create"})
     */
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