<?php

namespace Angelov\Donut\Behat\Pages\Users;

use Angelov\Donut\Behat\Pages\Page;

class UserProfilePage extends Page
{
    protected function getRoute(): string
    {
        return 'app.users.show';
    }

    public function countFriends() : int
    {
        $list = $this->getDocument()->findAll('css', '#friends-list li a');

        return count($list);
    }

    public function friendsListContainsUser(string $name) : bool
    {
        return $this->getDocument()->has('css', sprintf('#friends-list li a:contains("%s")', $name));
    }

    public function countMutualFriends() : int
    {
        $mutual = $this->getDocument()->findAll('css', '#mutual-friends-list li a');

        return count($mutual);
    }

    public function mutualFriendsListContainsUser(string $name) : bool
    {
        return $this->getDocument()->has('css', sprintf('#mutual-friends-list li a:contains("%s")', $name));
    }

    public function countThoughts() : int
    {
        return count($this->getDocument()->findAll('css', '#thoughts-list pre'));
    }

    public function hasNoFriendsMessage() : bool
    {
        return (bool) $this->getDocument()->has('css', '#friends-list li:contains("The user has no friends.")');
    }

    public function hasNoMutualFriendsMessage() : bool
    {
        return $this->getDocument()->has('css', '#mutual-friends-list li:contains("You don\'t have any mutual friends.")');
    }

    public function hasNoSharedThoughtsMessage() : bool
    {
        return $this->getDocument()->has('css', '#thoughts-list p:contains("The user hasn\'t shared any thoughts yet.")');
    }
}
