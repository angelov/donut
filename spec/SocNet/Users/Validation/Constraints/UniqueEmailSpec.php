<?php

namespace spec\SocNet\Users\Validation\Constraints;

use SocNet\Users\Validation\Constraints\UniqueEmail;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Validator\Constraint;

class UniqueEmailSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(UniqueEmail::class);
    }

    function it_is_constraint()
    {
        $this->shouldBeAnInstanceOf(Constraint::class);
    }
}
