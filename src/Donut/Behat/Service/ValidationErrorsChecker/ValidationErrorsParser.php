<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace Angelov\Donut\Behat\Service\ValidationErrorsChecker;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;

class ValidationErrorsParser implements ValidationErrorsParserInterface
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getMessages(): array
    {
        return $this->extractMessages(
            $this->findValidationErrorElements()
        );
    }

    /**
     * @return NodeElement[]
     */
    private function findValidationErrorElements() : array
    {
        return $this->session->getPage()->findAll('css', '.validation_error');
    }

    /**
     * @param NodeElement[] $elements
     * @return string[]
     */
    private function extractMessages(array $elements) : array
    {
        $extractor = function (NodeElement $element) : string {
            return $element->getText();
        };

        return array_map($extractor, $elements);
    }
}
