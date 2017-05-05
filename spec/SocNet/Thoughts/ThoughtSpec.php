<?php

namespace spec\SocNet\Thoughts;

use SocNet\Thoughts\Thought;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SocNet\Users\User;

class ThoughtSpec extends ObjectBehavior
{
    const THOUGHT_ID = 'uuid value';
    const THOUGHT_CONTENT = 'example';

    function let(User $user)
    {
        $this->beConstructedWith(self::THOUGHT_ID, $user, self::THOUGHT_CONTENT);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Thought::class);
    }

    function it_has_id_by_default()
    {
        $this->getId()->shouldReturn(self::THOUGHT_ID);
    }

    function it_has_mutable_id()
    {
        $this->setId('new id');
        $this->getId()->shouldReturn('new id');
    }

    function it_has_content_by_default()
    {
        $this->getContent()->shouldReturn(self::THOUGHT_CONTENT);
    }

    function it_has_mutable_content()
    {
        $this->setContent('example 2');
        $this->getContent()->shouldReturn('example 2');
    }

    function it_has_created_at_date_by_default()
    {
        $this->getCreatedAt()->shouldBeAnInstanceOf(\DateTime::class);
    }

    function it_has_mutable_created_at_date(\DateTime $dateTime)
    {
        $this->setCreatedAt($dateTime);
        $this->getCreatedAt()->shouldReturn($dateTime);
    }

    function it_can_be_constructed_with_created_at_date(User $user, \DateTime $dateTime)
    {
        $this->beConstructedWith(self::THOUGHT_ID, $user, self::THOUGHT_CONTENT, $dateTime);

        $this->getCreatedAt()->shouldReturn($dateTime);
    }

    function it_has_author_by_default(User $user)
    {
        $this->getAuthor()->shouldReturn($user);
    }

    function it_has_mutable_author(User $user2)
    {
        $this->setAuthor($user2);
        $this->getAuthor()->shouldReturn($user2);
    }
}
