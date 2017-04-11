<?php

namespace spec\SocNet\Core\Exceptions;

use SocNet\Core\Exceptions\ResourceNotFoundException;
use PhpSpec\ObjectBehavior;

class ResourceNotFoundExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ResourceNotFoundException::class);
    }

    function it_is_runtime_exception()
    {
        $this->shouldBeAnInstanceOf(\RuntimeException::class);
    }
}
