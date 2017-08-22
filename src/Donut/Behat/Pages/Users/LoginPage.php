<?php

namespace Angelov\Donut\Behat\Pages\Users;

use Angelov\Donut\Behat\Pages\Page;

class LoginPage extends Page
{
    protected function getRoute() : string
    {
        return 'security_login';
    }

    public function specifyEmail(string $email) : void
    {
        $this->getDocument()->fillField('Username', $email); // @todo change label to E-mail
    }

    public function specifyPassword(string $password) : void
    {
        $this->getDocument()->fillField('Password', $password);
    }

    public function login() : void
    {
        $this->getDocument()->pressButton('Login');
    }
}
