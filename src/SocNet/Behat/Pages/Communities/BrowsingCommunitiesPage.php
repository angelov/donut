<?php

namespace SocNet\Behat\Pages\Communities;

use Behat\Mink\Element\NodeElement;
use SocNet\Behat\Pages\Communities\CommunityCard;
use SocNet\Behat\Pages\Page;

class BrowsingCommunitiesPage extends Page
{
    protected function getRoute(): string
    {
        return 'app.communities.index';
    }

    public function countDisplayedCommunities() : int
    {
        $communities = $this->getDocument()->findAll('css', '.community');

        return count($communities);
    }

    public function getCommunityCard(string $name) : CommunityCard
    {
        return new CommunityCard(
            // sprintf('.community .media-body .community-name:contains("%s")
            $this->getDocument()->find('css', sprintf('.community:contains("%s")', $name))
        );
    }

    public function joinCommunity(string $name) : void
    {
        $this->getCommunityCard($name)->join();
    }

    public function getDisplayedCommunityNames() : array
    {
        $elements = $this->getDocument()->findAll('css', '.community-name');

        $mapper = function (NodeElement $element) : string {
            return $element->getText();
        };

        return array_map($mapper, $elements);
    }

    public function hasNoCommunitiesMessage() : bool
    {
        $message = 'There aren\'t any communities available for you. Want to create one?';

        return $this->getDocument()->has('css', sprintf('p:contains("%s")', $message));
    }
}
