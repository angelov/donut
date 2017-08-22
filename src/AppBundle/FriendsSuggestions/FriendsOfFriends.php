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
use GraphAware\Common\Result\Record;
use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;
use GraphAware\Reco4PHP\Result\SingleScore;
use Angelov\Donut\Users\User;

class FriendsOfFriends extends SingleDiscoveryEngine
{
    /**
     * The statement to be executed for finding items to be recommended.
     *
     * @param Node $input
     * @param Context $context
     *
     * @return Statement
     */
    public function discoveryQuery(Node $input, Context $context): StatementInterface
    {
        $query = '
            MATCH (input:User) WHERE id(input) = {id}
            MATCH (input)-[:FRIEND]->(friend:User)-[:FRIEND]->(friendOfFriend:User)
            RETURN friendOfFriend as reco, count(*) as score
        ';

        return Statement::create($query, ['id' => $input->identity()]);
    }

    /**
     * @return string The name of the discovery engine
     */
    public function name(): string
    {
        return 'friends_of_friends';
    }

    public function buildScore(Node $input, Node $item, Record $record, Context $context): SingleScore
    {
        return new SingleScore($record->get('score'));
    }
}
