<?php

namespace SocNet\Thoughts\Handlers;

use SocNet\Core\EventBus\EventBusInterface;
use SocNet\Thoughts\Commands\DeleteThoughtCommand;
use SocNet\Thoughts\Events\ThoughtWasDeletedEvent;
use SocNet\Thoughts\Repositories\ThoughtsRepositoryInterface;

class DeleteThoughtCommandHandler
{
    private $thoughts;
    private $events;

    public function __construct(ThoughtsRepositoryInterface $thoughts, EventBusInterface $events)
    {
        $this->thoughts = $thoughts;
        $this->events = $events;
    }

    public function handle(DeleteThoughtCommand $command)
    {
        $thought = $command->getThought();

        $this->thoughts->destroy($thought);

        $this->events->fire(new ThoughtWasDeletedEvent($thought));
    }
}
