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

namespace AppBundle\FriendsSuggestions;

use GraphAware\Common\Cypher\Statement;
use GraphAware\Common\Cypher\StatementInterface;
use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;

class LivingInSameCity extends SingleDiscoveryEngine
{
    public function discoveryQuery(Node $input, Context $context): StatementInterface
    {
        $query = '
            MATCH (input:User) WHERE id(input) = {id}
            MATCH (input)-[:LIVES_IN]->(c:City)<-[:LIVES_IN]->(reco:User)
            RETURN reco
        ';

        return Statement::create($query, ['id' => $input->identity()]);
    }

    public function name(): string
    {
        return 'living_in_same_city';
    }
}
