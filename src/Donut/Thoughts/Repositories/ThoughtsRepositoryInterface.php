<?php

namespace Angelov\Donut\Thoughts\Repositories;

use Angelov\Donut\Thoughts\Thought;

interface ThoughtsRepositoryInterface
{
    public function store(Thought $thought) : void;

    public function destroy(Thought $thought) : void;
}
