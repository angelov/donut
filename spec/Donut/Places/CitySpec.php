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

namespace spec\Angelov\Donut\Places;

use Angelov\Donut\Places\City;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Users\User;

class CitySpec extends ObjectBehavior
{
    const CITY_ID = 'uuid value';
    const CITY_NAME = 'Skopje';

    function let()
    {
        $this->beConstructedWith(self::CITY_ID, self::CITY_NAME);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(City::class);
    }

    function it_has_id_by_default()
    {
        $this->getId()->shouldReturn(self::CITY_ID);
    }

    function it_has_mutable_id()
    {
        $this->setId('a');
        $this->getId()->shouldReturn('a');
    }

    function it_has_a_name_by_default()
    {
        $this->getName()->shouldReturn(self::CITY_NAME);
    }

    function it_has_mutable_name()
    {
        $newName = 'Bitola';

        $this->setName($newName);
        $this->getName()->shouldReturn($newName);
    }

    function it_has_no_residents_by_default()
    {
        $this->getResidents()->shouldHaveCount(0);
    }
    
    function it_can_have_multiple_residents(User $first, User $second)
    {
        $first->setCity($this)->shouldBeCalled();
        $second->setCity($this)->shouldBeCalled();

        $this->addResident($first);
        $this->addResident($second);

        $this->getResidents()->shouldHaveCount(2);
    }

    function it_does_not_duplicate_residents(User $first)
    {
        $first->setCity($this)->shouldBeCalledTimes(1);

        $this->addResident($first);
        $this->addResident($first);

        $this->getResidents()->shouldHaveCount(1);
    }
}
