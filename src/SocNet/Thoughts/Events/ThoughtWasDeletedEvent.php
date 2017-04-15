<?php

namespace SocNet\Thoughts\Events;

use SocNet\Thoughts\Thought;

class ThoughtWasDeletedEvent
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
