<?php

namespace SocNet\Behat\Pages\Communities;

use Behat\Mink\Element\NodeElement;
use SocNet\Behat\Pages\Page;
use SocNet\Behat\Service\ElementsTextExtractor;

class CommunityPreviewPage extends Page
{
    protected function getRoute(): string
    {
        return 'app.communities.show';
    }

    /**
     * @psalm-suppress PossiblyNullReference
     */
    public function getDescription() : string
    {
        return $this->getDocument()->find('css', '#community-description')->getText();
    }

    /**
     * @psalm-suppress PossiblyNullReference
     */
    public function getAuthorName() : string
    {
        return $this->getDocument()->find('css', '#community-author')->getText();
    }

    /**
     * @psalm-suppress PossiblyNullReference
     */
    public function getCreationDate() : string
    {
        return $this->getDocument()->find('css', '#community-creation-date')->getText();
    }

    public function hasMembersList() : bool
    {
        return $this->getDocument()->has('css', 'ul.community-members');
    }

    public function getDisplayedMembers() : array
    {
        $elements = $this->getDocument()->findAll('css', 'ul.community-members li');

        return ElementsTextExtractor::fromElements($elements);
    }

    public function countDisplayedMembers() : int
    {
        return count(
            $this->getDocument()->findAll('css', 'ul.community-members li')
        );
    }

    public function join() : void
    {
        $this->getDocument()->pressButton('Join');
    }

    public function leave() : void
    {
        $this->getDocument()->pressButton('Leave');
    }
}
