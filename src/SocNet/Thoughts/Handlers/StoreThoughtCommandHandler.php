<?php

namespace SocNet\Thoughts\Handlers;

use SocNet\Core\EventBus\EventBusInterface;
use SocNet\Thoughts\Commands\StoreThoughtCommand;
use SocNet\Thoughts\Events\ThoughtWasPublishedEvent;
use SocNet\Thoughts\Repositories\ThoughtsRepositoryInterface;
use SocNet\Thoughts\Thought;

class StoreThoughtCommandHandler
{
    private $thoughts;
    private $events;

    public function __construct(ThoughtsRepositoryInterface $thoughts, EventBusInterface $events)
    {
        $this->thoughts = $thoughts;
        $this->events = $events;
    }

    public function handle(StoreThoughtCommand $command) : void
    {
        $thought = new Thought(
            $command->getAuthor(),
            $command->getContent()
        );

        $this->thoughts->store($thought);

        $this->events->fire(new ThoughtWasPublishedEvent($thought));
    }
}
