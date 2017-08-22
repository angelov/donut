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

namespace spec\Angelov\Donut\Communities\Commands;

use Angelov\Donut\Users\User;
use Angelov\Donut\Communities\Commands\StoreCommunityCommand;
use PhpSpec\ObjectBehavior;

class StoreCommunityCommandSpec extends ObjectBehavior
{
    const COMMUNITY_ID = 'uuid value';
    const COMMUNITY_NAME = 'Example';
    const COMMUNITY_DESCRIPTION = 'Just an example community';

    function let(User $author)
    {
        $id = self::COMMUNITY_ID;
        $name = self::COMMUNITY_NAME;
        $description = self::COMMUNITY_DESCRIPTION;

        $this->beConstructedWith($id, $name, $author, $description);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreCommunityCommand::class);
    }

    function it_holds_the_id()
    {
        $this->getId()->shouldReturn(self::COMMUNITY_ID);
    }

    function it_holds_the_community_name()
    {
        $this->getName()->shouldReturn('Example');
    }

    function it_holds_the_community_author(User $author)
    {
        $this->getAuthor()->shouldReturn($author);
    }

    function it_holds_the_community_description()
    {
        $this->getDescription()->shouldReturn(self::COMMUNITY_DESCRIPTION);
    }
}
