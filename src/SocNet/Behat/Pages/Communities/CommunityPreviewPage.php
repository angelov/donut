<?php

namespace SocNet\Behat\Pages\Communities;

use Behat\Mink\Element\NodeElement;
use SocNet\Behat\Pages\Page;

class CommunityPreviewPage extends Page
{
    protected function getRoute(): string
    {
        return 'app.communities.show';
    }

    public function getDescription() : string
    {
        return $this->getDocument()->find('css', '#community-description')->getText();
    }

    public function getAuthorName() : string
    {
        return $this->getDocument()->find('css', '#community-author')->getText();
    }

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

        $mapper = function (NodeElement $element) : string {
            return $element->getText();
        };

        return array_map($mapper, $elements);
    }

    public function countDisplayedMembers()
    {
        return count(
            $this->getDocument()->findAll('css', 'ul.community-members li')
        );
    }

    public function join()
    {
        $this->getDocument()->pressButton('Join');
    }

    public function leave()
    {
        $this->getDocument()->pressButton('Leave');
    }
}
