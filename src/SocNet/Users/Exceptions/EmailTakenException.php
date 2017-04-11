<?php

namespace SocNet\Users\Exceptions;

use RuntimeException;

class EmailTakenException extends RuntimeException
{
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;

        parent::__construct(sprintf('The provided email address [%s] is already in use', $email));
    }

    public function getEmail()
    {
        return $this->email;
    }
}