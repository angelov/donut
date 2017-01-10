<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdministratorVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // The administrators can do anything.
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $user->isIsAdmin();
    }
}
