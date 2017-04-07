<?php

namespace spec\SocNet\Communities;

use AppBundle\Entity\User;
use SocNet\Communities\Community;
use PhpSpec\ObjectBehavior;
use SocNet\Communities\Exceptions\CommunityMemberNotFoundException;

class CommunitySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Community::class);
    }

    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn('');
    }

    function it_has_no_name_by_default()
    {
        $this->getName()->shouldReturn('');
    }

    function it_has_mutable_name()
    {
        $this->setName('Example');
        $this->getName()->shouldReturn('Example');
    }

    function it_has_mutable_description()
    {
        $this->setDescription('This is an example');
        $this->getDescription()->shouldReturn('This is an example');
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
