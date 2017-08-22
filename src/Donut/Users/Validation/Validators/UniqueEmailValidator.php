<?php

namespace Angelov\Donut\Users\Validation\Validators;

use Angelov\Donut\Users\EmailAvailabilityChecker\EmailAvailabilityCheckerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    private $emailAvailabilityChecker;

    public function __construct(EmailAvailabilityCheckerInterface $emailAvailabilityChecker)
    {
        $this->emailAvailabilityChecker = $emailAvailabilityChecker;
    }

    public function validate($value, Constraint $constraint) : void
    {
        if ($this->emailAvailabilityChecker->isTaken($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }
    }
}
