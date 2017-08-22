<?php

namespace Angelov\Donut\Thoughts\Handlers;

use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Thoughts\Commands\DeleteThoughtCommand;
use Angelov\Donut\Thoughts\Events\ThoughtWasDeletedEvent;
use Angelov\Donut\Thoughts\Repositories\ThoughtsRepositoryInterface;

class DeleteThoughtCommandHandler
{
    private $thoughts;
    private $events;

    public function __construct(ThoughtsRepositoryInterface $thoughts, EventBusInterface $events)
    {
        $this->thoughts = $thoughts;
        $this->events = $events;
    }

    public function handle(DeleteThoughtCommand $command) : void
    {
        $thought = $command->getThought();

        $this->thoughts->destroy($thought);

        $this->events->fire(new ThoughtWasDeletedEvent($thought));
    }
}
