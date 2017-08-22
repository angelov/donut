<?php

namespace Angelov\Donut\Users\Validation\Constraints;

use Angelov\Donut\Users\Validation\Validators\UniqueEmailValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueEmail extends Constraint
{
    public $message = 'The given e-mail address [%string%] is already in use.';

    public function validatedBy()
    {
        return UniqueEmailValidator::class;
    }
}
