<?php

namespace spec\SocNet\Behat\Service\ValidationErrorsChecker;

use SocNet\Behat\Service\ValidationErrorsChecker\GlobalValidationErrorsChecker;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SocNet\Behat\Service\ValidationErrorsChecker\ValidationErrorsCheckerInterface;
use SocNet\Behat\Service\ValidationErrorsChecker\ValidationErrorsParserInterface;

class GlobalValidationErrorsCheckerSpec extends ObjectBehavior
{
    const FIELD = 'email_address';

    function let(ValidationErrorsParserInterface $errorsParser)
    {
        $this->beConstructedWith($errorsParser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GlobalValidationErrorsChecker::class);
    }

    function it_is_validation_errors_checker()
    {
        $this->shouldImplement(ValidationErrorsCheckerInterface::class);
    }

    function it_returns_true_when_the_message_is_found(ValidationErrorsParserInterface $errorsParser)
    {
        $errorsParser->getMessages()->willReturn(['msg1', 'msg2']);

        $this->checkMessageForField(self::FIELD, 'msg2')->shouldReturn(true);
    }

    function it_returns_false_when_the_message_is_not_found()
    {
        $this->checkMessageForField(self::FIELD, 'msg')->shouldReturn(false);
    }
}
