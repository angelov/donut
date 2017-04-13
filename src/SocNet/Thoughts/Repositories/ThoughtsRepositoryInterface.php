<?php

namespace SocNet\Thoughts\Repositories;

use SocNet\Thoughts\Thought;

interface ThoughtsRepositoryInterface
{
    public function store(Thought $thought) : void;

    public function destroy(Thought $thought);
}
