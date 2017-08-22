<?php

namespace spec\Angelov\Donut\Users\Validation\Constraints;

use Angelov\Donut\Users\Validation\Constraints\UniqueEmail;
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
