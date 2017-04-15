<?php

namespace SocNet\Thoughts\EventSubscribers;

use SocNet\Thoughts\Events\ThoughtWasPublishedEvent;
use SocNet\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;

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
