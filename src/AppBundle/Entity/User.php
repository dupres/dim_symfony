<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
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

    public function getFullname()
    {
        return $this->fullname;
    }

    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    }


    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password){
        $this->password=$password;
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

}