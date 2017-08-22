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

namespace spec\Angelov\Donut\Behat\Service\Storage;

use Angelov\Donut\Behat\Service\Storage\InMemoryStorage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Behat\Service\Storage\StorageInterface;

class InMemoryStorageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(InMemoryStorage::class);
    }

    function it_is_storage()
    {
        $this->shouldImplement(StorageInterface::class);
    }

    function it_sets_values_for_keys()
    {
        $this->set('a', 1);
        $this->set('b', 'c');

        $this->get('a')->shouldReturn(1);
        $this->get('b')->shouldReturn('c');
    }

    function it_overrides_values_for_key()
    {
        $this->set('a', 1);
        $this->set('a', 5);

        $this->get('a')->shouldReturn(5);
    }

    function it_returns_default_value_if_key_is_not_found()
    {
        $defaultValue = 4;

        $this->get('a', $defaultValue)->shouldReturn($defaultValue);
    }

    function it_returns_null_if_key_is_not_found_and_no_default_value_is_specified()
    {
        $this->get('a')->shouldReturn(null);
    }

    function it_is_empty_by_default()
    {
        $this->all()->shouldReturn([]);
    }

    function it_returns_all_stored_elements()
    {
        $this->set('a', 1);
        $this->set('b', 2);

        $all = $this->all();
        $all->shouldHaveCount(2);
        $all->shouldHaveKeyWithValue('a', 1);
        $all->shouldHaveKeyWithValue('b', 2);
    }

    function it_removes_all_stored_elements()
    {
        $this->set('a', 1);
        $this->set('b', 2);

        $this->clear();

        $this->all()->shouldHaveCount(0);
    }
}
