<?php

namespace spec\Angelov\Donut\Users\Exceptions;

use Angelov\Donut\Users\Exceptions\EmailTakenException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EmailTakenExceptionSpec extends ObjectBehavior
{
    const TAKEN_EMAIL = 'john@example.com';

    function let()
    {
        $this->beConstructedWith(self::TAKEN_EMAIL);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EmailTakenException::class);
    }

    function it_is_runtime_exception()
    {
        $this->shouldBeAnInstanceOf(\RuntimeException::class);
    }

    function it_contains_the_email_address()
    {
        $this->getEmail()->shouldReturn(self::TAKEN_EMAIL);
    }

    function it_has_a_message_by_default()
    {
        $this->getMessage()->shouldReturn(sprintf(
            'The provided email address [%s] is already in use',
            self::TAKEN_EMAIL
        ));
    }
}
