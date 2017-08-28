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

namespace spec\Angelov\Donut\Users\Commands;

use Angelov\Donut\Places\City;
use Angelov\Donut\Users\Commands\StoreUserCommand;
use PhpSpec\ObjectBehavior;

class StoreUserCommandSpec extends ObjectBehavior
{
    const USER_ID = 'uuid value';
    const USER_NAME = 'John';
    const USER_EMAIL = 'john@example.com';
    const USER_PASSWORD = '123456';
    const CITY_ID = 'city id';

    function let()
    {
        $this->beConstructedWith(self::USER_ID, self::USER_NAME, self::USER_EMAIL, self::USER_PASSWORD, self::CITY_ID);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreUserCommand::class);
    }

    function it_holds_the_id()
    {
        $this->getId()->shouldReturn(self::USER_ID);
    }

    function it_holds_the_user_name()
    {
        $this->getName()->shouldReturn(self::USER_NAME);
    }

    function it_holds_the_user_email()
    {
        $this->getEmail()->shouldReturn(self::USER_EMAIL);
    }

    function it_holds_the_user_password()
    {
        $this->getPassword()->shouldReturn(self::USER_PASSWORD);
    }

    function it_holds_the_city_id()
    {
        $this->getCityId()->shouldReturn(self::CITY_ID);
    }
}
