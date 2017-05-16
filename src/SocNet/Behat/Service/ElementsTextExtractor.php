<?php

namespace SocNet\Behat\Service;

use Behat\Mink\Element\NodeElement;
use InvalidArgumentException;

class ElementsTextExtractor
{
    /**
     * @param NodeElement[]
     * @return string[]
     * @throws InvalidArgumentException
     */
    public static function fromElements(array $elements) : array
    {
        $checker = function ($element) : void {
            if (!$element instanceof NodeElement) {
                throw new InvalidArgumentException();
            }
        };

        array_walk($elements, $checker);

        $extractor = function (NodeElement $element) : string {
            return $element->getText();
        };

        return array_map($extractor, $elements);
    }
}
