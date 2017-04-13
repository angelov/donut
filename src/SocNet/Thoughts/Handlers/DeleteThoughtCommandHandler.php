<?php

namespace SocNet\Thoughts\Handlers;

use SocNet\Thoughts\Commands\DeleteThoughtCommand;
use SocNet\Thoughts\Repositories\ThoughtsRepositoryInterface;

class DeleteThoughtCommandHandler
{
    private $thoughts;

    public function __construct(ThoughtsRepositoryInterface $thoughts)
    {
        $this->thoughts = $thoughts;
    }

    public function handle(DeleteThoughtCommand $command)
    {
        $thought = $command->getThought();

        $this->thoughts->destroy($thought);
    }
}
