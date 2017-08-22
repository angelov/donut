<?php

namespace Angelov\Donut\Behat\Pages\Communities;

use Behat\Mink\Element\NodeElement;

class CommunityCard
{
    private $element;

    public function __construct(NodeElement $element)
    {
        $this->element = $element;
    }

    public function hasJoinButton() : bool
    {
        return $this->element->hasButton('Join');
    }

    public function hasViewButton() : bool
    {
        return $this->element->hasLink('View');
    }

    public function join() : void
    {
        $this->element->pressButton('Join');
    }

    public function getDescription() : string
    {
        return $this->element->find('css', '.community-description')->getText();
    }

    public function getAuthorName() : string
    {
        return $this->element->find('css', '.community-author')->getText();
    }
}
