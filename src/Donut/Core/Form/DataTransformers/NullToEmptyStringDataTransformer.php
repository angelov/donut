<?php

namespace Angelov\Donut\Core\Form\DataTransformers;

use Symfony\Component\Form\DataTransformerInterface;

class NullToEmptyStringDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return $value;
    }

    public function reverseTransform($value)
    {
        return $value ?? '';
    }
}
