<?php

namespace SocNet\Thoughts\Events;

use JMS\Serializer\Annotation as Serializer;
use SocNet\Thoughts\Thought;

class ThoughtWasPublishedEvent
{
    /**
     * @Serializer\Type(name="SocNet\Thoughts\Thought")
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
