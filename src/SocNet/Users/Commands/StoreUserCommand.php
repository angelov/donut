<?php

namespace SocNet\Users\Commands;

use SocNet\Users\Validation\Constraints\UniqueEmail;
use Symfony\Component\Validator\Constraints as Assert;

class StoreUserCommand
{
    /**
     * @Assert\NotBlank(message="Please enter your name.")
     */
    private $name;

    /**
     * @Assert\Email()
     * @Assert\NotBlank(message="Please enter your email.")
     * @UniqueEmail(message="The email is already in use.")
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Please enter your password.")
     * @Assert\Length(min="6", minMessage="The password must be at least 6 characters long.")
     */
    private $password;

    public function __construct(string $name, string $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getPassword() : string
    {
        return $this->password;
    }
}
