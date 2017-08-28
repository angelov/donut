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
use Angelov\Donut\Communities\Commands\LeaveCommunityCommand;
use Angelov\Donut\Communities\Community;
use Angelov\Donut\Communities\Exceptions\CommunityMemberNotFoundException;
use Angelov\Donut\Communities\Handlers\LeaveCommunityCommandHandler;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Communities\Repositories\CommunitiesRepositoryInterface;

class LeaveCommunityCommandHandlerSpec extends ObjectBehavior
{
    function let(
        CommunitiesRepositoryInterface $communities,
        UsersRepositoryInterface $users,
        LeaveCommunityCommand $command,
        User $user,
        Community $community
    ) {
        $this->beConstructedWith($communities, $users);

        $command->getUserId()->willReturn('user id');
        $command->getCommunityId()->willReturn('community id');

        $communities->find('community id')->willReturn($community);
        $users->find('user id')->willReturn($user);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LeaveCommunityCommandHandler::class);
    }

    function it_throws_exception_if_the_user_is_not_found(LeaveCommunityCommand $command, UsersRepositoryInterface $users)
    {
        $users->find('user id')->shouldBeCalled()->willThrow(ResourceNotFoundException::class);

        $this->shouldThrow(ResourceNotFoundException::class)->during('handle', [$command]);
    }

    function it_throws_exception_if_the_community_is_not_found(LeaveCommunityCommand $command, CommunitiesRepositoryInterface $communities)
    {
        $communities->find('community id')->shouldBeCalled()->willThrow(ResourceNotFoundException::class);

        $this->shouldThrow(ResourceNotFoundException::class)->during('handle', [$command]);
    }

    function it_removes_the_member_from_the_community(
        LeaveCommunityCommand $command,
        CommunitiesRepositoryInterface $communities,
        Community $community,
        User $user
    ) {
        $community->removeMember($user)->shouldBeCalled();
        $communities->store($community)->shouldBeCalled();

        $this->handle($command);
    }

    function it_throws_member_not_found_exception_when_trying_to_remove_non_member(
        LeaveCommunityCommand $command,
        Community $community,
        User $user
    ) {
        $community->removeMember($user)->willThrow(CommunityMemberNotFoundException::class);

        $this->shouldThrow(CommunityMemberNotFoundException::class)->during('handle', [$command]);
    }
}
