<?php

namespace Angelov\Donut\Users\Commands;

use Angelov\Donut\Places\City;
use Angelov\Donut\Users\Validation\Constraints\UniqueEmail;
use Symfony\Component\Validator\Constraints as Assert;

class StoreUserCommand
{
    private $id;

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

    private $city;

    public function __construct(string $id, string $name, string $email, string $password, City $city)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->city = $city;
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
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

    public function getCity() : City
    {
        return $this->city;
    }
}
