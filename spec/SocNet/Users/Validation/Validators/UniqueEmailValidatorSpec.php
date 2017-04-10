<?php

namespace spec\SocNet\Users\Validation\Validators;

use SocNet\Users\EmailAvailabilityChecker\EmailAvailabilityCheckerInterface;
use SocNet\Users\Validation\Constraints\UniqueEmail;
use SocNet\Users\Validation\Validators\UniqueEmailValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class UniqueEmailValidatorSpec extends ObjectBehavior
{
    const EMAIL_ADDRESS = 'john@example.com';

    function let(EmailAvailabilityCheckerInterface $emailAvailabilityChecker)
    {
        $this->beConstructedWith($emailAvailabilityChecker);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UniqueEmailValidator::class);
    }

    function it_is_constraint_validator()
    {
        $this->shouldBeAnInstanceOf(ConstraintValidator::class);
    }

    function it_does_not_cause_violation_for_available_email_address(
        EmailAvailabilityCheckerInterface $emailAvailabilityChecker,
        Constraint $constraint
    ) {
        $emailAvailabilityChecker->isTaken(self::EMAIL_ADDRESS)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->validate(self::EMAIL_ADDRESS, $constraint);
    }

    function it_causes_violation_for_taken_email_address(
        EmailAvailabilityCheckerInterface $emailAvailabilityChecker,
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $builder
    ) {
        $constraint = new UniqueEmail();

        $emailAvailabilityChecker->isTaken(self::EMAIL_ADDRESS)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->initialize($context);
        $context->buildViolation(Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn($builder);

        $builder->setParameter('%string%', self::EMAIL_ADDRESS)
            ->shouldBeCalled()
            ->willReturn($builder);

        $builder->addViolation()->shouldBeCalled();

        $this->validate(self::EMAIL_ADDRESS, $constraint);
    }
}
