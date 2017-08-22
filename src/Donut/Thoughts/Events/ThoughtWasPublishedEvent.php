<?php

namespace Angelov\Donut\Thoughts\Events;

use JMS\Serializer\Annotation as Serializer;
use Angelov\Donut\Thoughts\Thought;

class ThoughtWasPublishedEvent
{
    /**
     * @Serializer\Type(name="Angelov\Donut\Thoughts\Thought")
     */
    private $thought;

    public function __construct(Thought $thought)
    {
        $this->thought = $thought;
    }

    public function getThought() : Thought
    {
        return $this->thought;
    }
}
