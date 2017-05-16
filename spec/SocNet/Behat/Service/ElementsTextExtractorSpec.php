<?php

namespace spec\SocNet\Behat\Service;

use Behat\Mink\Element\NodeElement;
use SocNet\Behat\Service\ElementsTextExtractor;
use PhpSpec\ObjectBehavior;

class ElementsTextExtractorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ElementsTextExtractor::class);
    }

    function it_extracts_text_from_multiple_elements(NodeElement $first, NodeElement $second)
    {
        $first->getText()->shouldBeCalled()->willReturn('first');
        $second->getText()->shouldBeCalled()->willReturn('second');

        $this::fromElements([$first, $second])->shouldReturn(['first', 'second']);
    }

    function it_throws_exception_if_some_element_is_of_wrong_type(NodeElement $first)
    {
        $first->getText()->willReturn('this is okay');

        $this->shouldThrow(\InvalidArgumentException::class)->during('fromElements', [[$first, 'not an element']]);
    }
}
