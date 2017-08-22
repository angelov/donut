<?php

namespace Angelov\Donut\Thoughts\Handlers;

use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Thoughts\Commands\StoreThoughtCommand;
use Angelov\Donut\Thoughts\Events\ThoughtWasPublishedEvent;
use Angelov\Donut\Thoughts\Repositories\ThoughtsRepositoryInterface;
use Angelov\Donut\Thoughts\Thought;

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
            $command->getId(),
            $command->getAuthor(),
            $command->getContent(),
            $command->getCreatedAt()
        );

        $this->thoughts->store($thought);

        $this->events->fire(new ThoughtWasPublishedEvent($thought));
    }
}
