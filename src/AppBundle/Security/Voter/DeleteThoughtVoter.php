<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Thought;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeleteThoughtVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return $attribute === 'DELETE_THOUGHT';
    }

    /**
     * @param string $attribute
     * @param Thought $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($user->isIsAdmin()) {
            return true;
        }

        return $subject->getAuthor()->equals($user);
    }
}
