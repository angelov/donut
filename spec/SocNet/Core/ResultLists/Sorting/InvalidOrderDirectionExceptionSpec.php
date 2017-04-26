<?php

namespace spec\SocNet\Core\ResultLists\Sorting;

use Exception;
use SocNet\Core\ResultLists\Sorting\InvalidOrderDirectionException;
use PhpSpec\ObjectBehavior;
use SocNet\Core\ResultLists\Sorting\OrderDirection;

class InvalidOrderDirectionExceptionSpec extends ObjectBehavior
{
    const INVALID_ORDER_DIRECTION = 'BFS';

    function let()
    {
        $this->beConstructedWith(self::INVALID_ORDER_DIRECTION);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(InvalidOrderDirectionException::class);
    }

    function it_is_exception()
    {
        $this->shouldBeAnInstanceOf(Exception::class);
    }

    function it_holds_the_order_direction()
    {
        $this->getDirection()->shouldReturn(self::INVALID_ORDER_DIRECTION);
    }

    function it_has_a_message_by_default()
    {
        $this->getMessage()->shouldReturn(sprintf(
            '%s is not a valid ordering direction. Please check the "%s" class for valid directions.',
            self::INVALID_ORDER_DIRECTION,
            OrderDirection::class
        ));
    }
}
