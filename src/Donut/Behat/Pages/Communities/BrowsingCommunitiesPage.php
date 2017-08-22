<?php

namespace Angelov\Donut\Behat\Pages\Communities;

use Angelov\Donut\Behat\Pages\Page;
use Angelov\Donut\Behat\Service\ElementsTextExtractor;

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

        return ElementsTextExtractor::fromElements($elements);
    }

    public function hasNoCommunitiesMessage() : bool
    {
        $message = 'There aren\'t any communities available for you. Want to create one?';

        return $this->getDocument()->has('css', sprintf('p:contains("%s")', $message));
    }
}
