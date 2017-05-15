<?php

namespace SocNet\Behat\Pages\Thoughts;

use SocNet\Behat\Pages\Page;

class HomePage extends Page
{
    protected function getRoute(): string
    {
        return 'app.thoughts.index';
    }

    public function specifyThoughtContent(string $content) : void
    {
        $this->getDocument()->fillField('Content', $content);
    }

    public function shareThought() : void
    {
        $this->getDocument()->pressButton('Submit');
    }

    public function containsThought(string $thought) : bool
    {
        return $this->getDocument()->has('css', sprintf('.thought pre:contains("%s")', $thought));
    }

    public function countThoughtsFromAuthor(string $author) : int
    {
        $thoughts = $this->getDocument()->findAll('css', sprintf('.thought:contains("by %s")', $author));

        return count($thoughts);
    }

    /**
     * @todo create an ThoughtCard element?
     * @psalm-suppress PossiblyNullReference
     */
    public function deleteThought(string $thought) : void
    {
        $thought = $this->getDocument()->find('css', sprintf('pre:contains("%s")', $thought));

        $thought->getParent()->pressButton('delete');
    }
}
