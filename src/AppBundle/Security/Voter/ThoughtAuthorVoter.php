<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Thought;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ThoughtAuthorVoter extends Voter
{
    private $supportedActions = [
        'DELETE_THOUGHT'
    ];

    protected function supports($attribute, $subject)
    {
        if (!$subject instanceof Thought) {
            return false;
        }

        return in_array($attribute, $this->supportedActions);
    }

    /**
     * @param string $attribute
     * @param Thought $thought
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $thought, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $thought->getAuthor()->eqauls($user);
    }
}
