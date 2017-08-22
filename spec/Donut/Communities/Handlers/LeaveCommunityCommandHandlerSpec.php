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

use Angelov\Donut\Users\User;
use Prophecy\Argument;
use Angelov\Donut\Communities\Commands\LeaveCommunityCommand;
use Angelov\Donut\Communities\Community;
use Angelov\Donut\Communities\Exceptions\CommunityMemberNotFoundException;
use Angelov\Donut\Communities\Handlers\LeaveCommunityCommandHandler;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Communities\Repositories\CommunitiesRepositoryInterface;

class LeaveCommunityCommandHandlerSpec extends ObjectBehavior
{
    function let(CommunitiesRepositoryInterface $repository, LeaveCommunityCommand $command, User $user, Community $community)
    {
        $this->beConstructedWith($repository);

        $command->getUser()->willReturn($user);
        $command->getCommunity()->willReturn($community);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LeaveCommunityCommandHandler::class);
    }

    function it_removes_the_member_from_the_community(LeaveCommunityCommand $command, CommunitiesRepositoryInterface $repository, Community $community, User $user)
    {
        $community->removeMember($user)->shouldBeCalled();
        $repository->store($community)->shouldBeCalled();

        $this->handle($command);
    }

    function it_throws_member_not_found_exception_when_trying_to_remove_non_member(LeaveCommunityCommand $command, Community $community)
    {
        $community->removeMember(Argument::type(User::class))->willThrow(CommunityMemberNotFoundException::class);

        $this->shouldThrow(CommunityMemberNotFoundException::class)->during('handle', [$command]);

    }
}
