<?php

namespace Angelov\Donut\Thoughts\EventSubscribers;

use Angelov\Donut\Thoughts\Events\ThoughtWasDeletedEvent;
use Angelov\Donut\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;

class DecreaseThoughtsNumberForUser
{
    private $counter;

    public function __construct(ThoughtsCounterInterface $counter)
    {
        $this->counter = $counter;
    }

    public function notify(ThoughtWasDeletedEvent $event) : void
    {
        $thought = $event->getThought();
        $author = $thought->getAuthor();

        $this->counter->decrease($author);
    }
}
