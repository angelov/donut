<?php

namespace Angelov\Donut\Behat\Pages\Users;

use Angelov\Donut\Behat\Pages\Page;

class RegistrationPage extends Page
{
    protected function getRoute() : string
    {
        return 'app.users.register';
    }

    public function specifyName(string $name) : void
    {
        $this->getDocument()->fillField('Name', $name);
    }

    public function specifyEmail(string $email) : void
    {
        $this->getDocument()->fillField('Email', $email);
    }

    public function specifyPassword(string $password) : void
    {
        $this->getDocument()->fillField('Password', $password);
        $this->getDocument()->fillField('Repeat Password', $password);
    }

    public function confirmPassword(string $password) : void
    {
        $this->getDocument()->fillField('Repeat Password', $password);
    }

    public function chooseCity(string $cityName) : void
    {
        $this->getDocument()->selectFieldOption('City', $cityName);
    }

    public function register() : void
    {
        $this->getDocument()->pressButton('Register');
    }
}
