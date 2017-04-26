<?php

namespace AppBundle\FriendsSuggestions;

use GraphAware\Common\Cypher\Statement;
use GraphAware\Common\Cypher\StatementInterface;
use GraphAware\Common\Result\Record;
use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Context\Context;
use GraphAware\Reco4PHP\Engine\SingleDiscoveryEngine;
use GraphAware\Reco4PHP\Result\SingleScore;
use SocNet\Users\User;

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
