<?php

namespace SocNet\Thoughts\EventSubscribers;

use SocNet\Thoughts\Events\ThoughtWasDeletedEvent;
use SocNet\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;

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
