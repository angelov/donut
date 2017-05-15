<?php

namespace SocNet\Behat\Pages\Users;

use Behat\Mink\Element\NodeElement;
use SocNet\Behat\Pages\Users\UserCard;
use SocNet\Behat\Pages\Page;

class BrowsingUsersPage extends Page
{
    protected function getRoute(): string
    {
        return 'app.users.index';
    }

    public function getUserCard(string $name) : UserCard
    {
        $card = $this->getDocument()->find('css', sprintf('#users-list .user-card:contains("%s")', $name));

        return new UserCard($card);
    }

    public function countDisplayedUsers() : int
    {
        return count($this->getDocument()->findAll('css', '#users-list .user-card'));
    }

    public function getDisplayedUserNames() : array
    {
        $found = $this->getDocument()->findAll('css', '#users-list .user-card .user-name');

        $mapper = function(NodeElement $element) : string {
            return $element->getText();
        };

        return array_map($mapper, $found);
    }
}
