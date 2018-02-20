<?php

namespace AppBundle\Security\Authorization;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ShowVoter extends Voter
{
    public function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // Récupérer l'utilisateur
        $user = $token->getUser();

        //Récupérer le show -> $show
        $show = $subject;

        // Si $show->getAuthor() === $user -> return true
        if ($show->getAuthor() === $user){
            return true;
        }

        // Sinon, return false
        return false;
    }

    public function supports($attribute, $subject)
    {
        if ($subject instanceof Show){
            return true;
        }
        return false;
    }

}