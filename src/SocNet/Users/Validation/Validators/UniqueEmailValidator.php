<?php

namespace SocNet\Users\Validation\Validators;

use SocNet\Users\EmailAvailabilityChecker\EmailAvailabilityCheckerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    private $emailAvailabilityChecker;

    public function __construct(EmailAvailabilityCheckerInterface $emailAvailabilityChecker)
    {
        $this->emailAvailabilityChecker = $emailAvailabilityChecker;
    }

    public function validate($value, Constraint $constraint)
    {
        if ($this->emailAvailabilityChecker->isTaken($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }
    }
}
