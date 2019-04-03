<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

//quand on appelle une methode is_granted dans une vue ou dans un controllerc'est le voter qui est appelé
//checker la permission de l'utilisateur sur un objet // php bin /console make:voter
//le but c'est d'etre appelé à un instant de l'application -> is_granted

class LivreVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'DELETE']) //type d'action edit et delete
            && $subject instanceof \App\Entity\Livres;
    }

    protected function voteOnAttribute($attribute, $livre, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($user -> isAdmin())
        {
            return true;
        }

        if (null == $livre->getUser())
        {
            return false;
        }
        switch ($attribute) {
            case 'EDIT':
                return $livre->getUser()->getId() == $user->getId();
                break;
            case 'DELETE':
                return $livre->getUser()->getId() == $user->getId();
                break;
        }

        return false;
    }


}
