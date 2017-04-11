<?php

namespace spec\SocNet\Thoughts;

use SocNet\Thoughts\Thought;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SocNet\Users\User;

class ThoughtSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Thought::class);
    }

    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn('');
    }

    function it_has_mutable_content()
    {
        $this->setContent('example');
        $this->getContent()->shouldReturn('example');
    }

    function it_has_mutable_created_at_date(\DateTime $dateTime)
    {
        $this->setCreatedAt($dateTime);
        $this->getCreatedAt()->shouldReturn($dateTime);
    }

    function it_has_mutable_author(User $user)
    {
        $this->setAuthor($user);
        $this->getAuthor()->shouldReturn($user);
    }
}
