<?php

namespace SocNet\Thoughts\Events;

use SocNet\Thoughts\Thought;

class ThoughtWasPublishedEvent
{
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
