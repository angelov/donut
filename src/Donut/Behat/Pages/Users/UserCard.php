<?php

namespace Angelov\Donut\Behat\Pages\Users;

use Behat\Mink\Element\NodeElement;

class UserCard
{
    private $element;

    public function __construct(NodeElement $element)
    {
        $this->element = $element;
    }

    public function getNumberOfThoughts() : int
    {
        return (int) $this->extractBadgeValue('thoughts');
    }

    public function getNumberOfFriends() : int
    {
        return (int) $this->extractBadgeValue('friends');
    }

    public function getNumberOfMutualFriends() : int
    {
        return (int) $this->extractBadgeValue('mutual friends');
    }

    public function getMutualFriendsNames() : array
    {
        $names = $this->element->find('css', '.mutual-friends-list .mutual-friend-name')->getText();

        return explode(', ', $names);
    }

    public function getEmail() : string
    {
        return $this->element->find('css', '.badge[data-type="email"]')->getText();
    }

    private function extractBadgeValue($badge) : string
    {
        $badge = $this->element->find('css', sprintf('.badge:contains(" %s")', $badge));

        return explode(' ', $badge->getText())[0];
    }

    public function addAsFriend() : void
    {
        $this->element->clickLink('Add friend');
    }

    protected function getElement() : NodeElement
    {
        return $this->element;
    }
}
