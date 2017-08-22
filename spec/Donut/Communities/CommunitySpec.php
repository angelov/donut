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

namespace spec\Angelov\Donut\Communities;

use Prophecy\Argument;
use Angelov\Donut\Users\User;
use Angelov\Donut\Communities\Community;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Communities\Exceptions\CommunityMemberNotFoundException;

class CommunitySpec extends ObjectBehavior
{
    const COMMUNITY_ID = 'uuid value';
    const COMMUNITY_NAME = 'Example';
    const COMMUNITY_DESCRIPTION = 'This is an example';

    function let(User $author)
    {
        $this->beConstructedWith(self::COMMUNITY_ID, self::COMMUNITY_NAME, $author, self::COMMUNITY_DESCRIPTION);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Community::class);
    }

    function it_has_id_by_default()
    {
        $this->getId()->shouldReturn(self::COMMUNITY_ID);
    }

    function it_has_mutable_id()
    {
        $this->setId('new id');
        $this->getId()->shouldReturn('new id');
    }

    function it_has_name_by_default()
    {
        $this->getName()->shouldReturn(self::COMMUNITY_NAME);
    }

    function it_has_mutable_name()
    {
        $this->setName('Example 2');
        $this->getName()->shouldReturn('Example 2');
    }

    function it_has_description_by_default()
    {
        $this->getDescription()->shouldReturn(self::COMMUNITY_DESCRIPTION);
    }

    function it_has_mutable_description()
    {
        $this->setDescription('This is an example 2');
        $this->getDescription()->shouldReturn('This is an example 2');
    }

    function it_has_mutable_author(User $author)
    {
        $this->setAuthor($author);
        $this->getAuthor()->shouldReturn($author);
    }

    function it_has_no_members_by_default()
    {
        $this->getMembers()->shouldReturn([]);
    }

    function it_can_accept_new_members(User $first, User $second)
    {
        $this->addMember($first);
        $this->addMember($second);

        $this->getMembers()->shouldHaveCount(2);
        $this->getMembers()->shouldReturn([$first, $second]);
    }

    function it_does_not_readd_existing_members(User $first, User $second)
    {
        $this->addMember($first);
        $this->addMember($second);
        $this->addMember($first);

        $this->getMembers()->shouldHaveCount(2);
        $this->getMembers()->shouldReturn([$first, $second]);
    }

    function it_can_remove_members(User $member)
    {
        $this->addMember($member);

        $this->removeMember($member);

        $this->getMembers()->shouldHaveCount(0);
    }

    function it_throws_exception_when_removing_nonexisting_member(User $nonMember)
    {
        $nonMember->getName()->willReturn(Argument::type('string'));

        $this->shouldThrow(CommunityMemberNotFoundException::class)->during('removeMember', [$nonMember]);
    }

    function it_has_a_created_at_date_by_default()
    {
        $this->getCreatedAt()->shouldBeAnInstanceOf(\DateTime::class);
    }

    function it_has_a_mutable_created_at_date(\DateTime $dateTime)
    {
        $this->setCreatedAt($dateTime);
        $this->getCreatedAt()->shouldReturn($dateTime);
    }
}
