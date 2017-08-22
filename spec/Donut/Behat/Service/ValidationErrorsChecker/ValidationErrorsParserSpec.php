<?php

namespace spec\Angelov\Donut\Behat\Service\ValidationErrorsChecker;

use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Angelov\Donut\Behat\Service\ValidationErrorsChecker\ValidationErrorsParser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Behat\Service\ValidationErrorsChecker\ValidationErrorsParserInterface;

class ValidationErrorsParserSpec extends ObjectBehavior
{
    function let(Session $session, DocumentElement $page)
    {
        $this->beConstructedWith($session);

        $session->getPage()->willReturn($page);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ValidationErrorsParser::class);
    }

    function it_is_validation_errors_parser()
    {
        $this->shouldImplement(ValidationErrorsParserInterface::class);
    }

    function it_returns_empty_array_when_there_are_no_errors(DocumentElement $page)
    {
        $page->findAll('css', Argument::type('string'))->shouldBeCalled()->willReturn([]);

        $this->getMessages()->shouldReturn([]);
    }

    function it_returns_array_when_there_is_one_error(DocumentElement $page, NodeElement $errorElement)
    {
        $page->findAll('css', Argument::type('string'))->shouldBeCalled()->willReturn([$errorElement]);
        $errorElement->getText()->shouldBeCalled()->willReturn('Please enter your name');

        $this->getMessages()->shouldReturn(['Please enter your name']);
    }

    function it_returns_all_messages_when_there_are_multiple_errors(DocumentElement $page, NodeElement $firstError, NodeElement $secondError)
    {
        $page->findAll('css', Argument::type('string'))->shouldBeCalled()->willReturn([$firstError, $secondError]);
        $firstError->getText()->shouldBeCalled()->willReturn('Please enter your name');
        $secondError->getText()->shouldBeCalled()->willReturn('Please enter your e-mail address');

        $this->getMessages()->shouldReturn(['Please enter your name', 'Please enter your e-mail address']);
    }
}
