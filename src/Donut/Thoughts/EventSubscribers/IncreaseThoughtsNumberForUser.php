<?php

namespace Angelov\Donut\Thoughts\EventSubscribers;

use Angelov\Donut\Thoughts\Events\ThoughtWasPublishedEvent;
use Angelov\Donut\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;

class IncreaseThoughtsNumberForUser
{
    private $thoughtsCounter;

    public function __construct(ThoughtsCounterInterface $thoughtsCounter)
    {
        $this->thoughtsCounter = $thoughtsCounter;
    }

    public function notify(ThoughtWasPublishedEvent $event) : void
    {
        $thought = $event->getThought();
        $author = $thought->getAuthor();

        $this->thoughtsCounter->increase($author);
    }
}
