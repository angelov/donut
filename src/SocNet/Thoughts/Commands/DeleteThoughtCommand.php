<?php

namespace SocNet\Thoughts\Commands;

use SocNet\Thoughts\Thought;

class DeleteThoughtCommand
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
