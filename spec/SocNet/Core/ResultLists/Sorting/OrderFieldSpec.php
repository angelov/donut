<?php

namespace spec\SocNet\Core\ResultLists\Sorting;

use SocNet\Core\ResultLists\Sorting\InvalidOrderDirectionException;
use SocNet\Core\ResultLists\Sorting\OrderField;
use PhpSpec\ObjectBehavior;

class OrderFieldSpec extends ObjectBehavior
{
    const ORDER_FIELD = 'name';
    const ORDER_DIRECTION = 'ASC';

    function let()
    {
        $this->beConstructedWith(self::ORDER_FIELD, self::ORDER_DIRECTION);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(OrderField::class);
    }

    function it_cannot_be_constructed_with_invalid_direction()
    {
        $this->shouldThrow(InvalidOrderDirectionException::class)->during('__construct', [
            self::ORDER_FIELD,
            'GHS'
        ]);
    }

    function it_holds_the_field()
    {
        $this->getField()->shouldReturn(self::ORDER_FIELD);
    }

    function it_holds_the_direction()
    {
        $this->getDirection()->shouldReturn(self::ORDER_DIRECTION);
    }
}
