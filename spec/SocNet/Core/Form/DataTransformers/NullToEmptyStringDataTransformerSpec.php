<?php

namespace spec\SocNet\Core\Form\DataTransformers;

use SocNet\Core\Form\DataTransformers\NullToEmptyStringDataTransformer;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\DataTransformerInterface;

class NullToEmptyStringDataTransformerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NullToEmptyStringDataTransformer::class);
    }

    function it_is_data_transformer()
    {
        $this->shouldBeAnInstanceOf(DataTransformerInterface::class);
    }

    function it_keeps_original_representation_untrasformed()
    {
        $this->transform('example')->shouldReturn('example');
        $this->transform(null)->shouldReturn(null);
    }

    function it_transforms_null_to_empty_string_when_doing_reverse_transformation()
    {
        $this->reverseTransform(null)->shouldReturn('');
    }

    function it_keeps_original_non_nullable_representation_when_doing_reverse_transformation()
    {
        $this->reverseTransform('something')->shouldReturn('something');
    }
}
