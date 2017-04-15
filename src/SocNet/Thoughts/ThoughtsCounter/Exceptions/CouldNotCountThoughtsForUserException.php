<?php

namespace SocNet\Thoughts\ThoughtsCounter\Exceptions;

use RuntimeException;
use SocNet\Users\User;

class CouldNotCountThoughtsForUserException extends RuntimeException
{
    private $user;
    private $reason;

    public function __construct(User $user, string $reason = '')
    {
        $this->user = $user;
        $this->reason = $reason;

        parent::__construct(sprintf(
            'Could not fetch number of thoughts for user [%d]: %s',
            $user->getId(),
            $reason
        ));
    }

    public function getUser() : User
    {
        return $this->user;
    }

    public function getReason() : string
    {
        return $this->reason;
    }
}
