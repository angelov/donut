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

namespace spec\Angelov\Donut\Communities\Handlers;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
use Angelov\Donut\Users\User;
use Prophecy\Argument;
use Angelov\Donut\Communities\Commands\StoreCommunityCommand;
use Angelov\Donut\Communities\Community;
use Angelov\Donut\Communities\Handlers\StoreCommunityCommandHandler;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Communities\Repositories\CommunitiesRepositoryInterface;

class StoreCommunityCommandHandlerSpec extends ObjectBehavior
{
    function let(
        CommunitiesRepositoryInterface $communities,
        UsersRepositoryInterface $users,
        StoreCommunityCommand $command,
        User $author
    ) {
        $this->beConstructedWith($communities, $users);

        $command->getId()->willReturn('uuid value');
        $command->getName()->willReturn('name');
        $command->getDescription()->willReturn('');
        $command->getAuthorId()->willReturn('author id');

        $users->find('author id')->willReturn($author);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreCommunityCommandHandler::class);
    }

    function it_throws_exception_if_the_author_is_not_found(UsersRepositoryInterface $users, StoreCommunityCommand $command)
    {
        $users->find('author id')->shouldBeCalled()->willThrow(ResourceNotFoundException::class);

        $this->shouldThrow(ResourceNotFoundException::class)->during('handle', [$command]);
    }

    function it_stores_new_communities(StoreCommunityCommand $command, CommunitiesRepositoryInterface $communities)
    {
        $command->getId()->shouldBeCalled();
        $command->getName()->shouldBeCalled();
        $command->getDescription()->shouldBeCalled();
        $command->getAuthorId()->shouldBeCalled();

        $communities->store(Argument::type(Community::class))->shouldBeCalled();

        $this->handle($command);
    }
}
