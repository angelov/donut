<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

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
