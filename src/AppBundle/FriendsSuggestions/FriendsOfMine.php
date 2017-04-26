<?php

namespace AppBundle\FriendsSuggestions;

use GraphAware\Common\Cypher\Statement;
use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Filter\BaseBlacklistBuilder;

class FriendsOfMine extends BaseBlacklistBuilder
{
    /**
     * @param Node $input
     * @return Statement
     */
    public function blacklistQuery(Node $input) : Statement
    {
        $query = '
            MATCH (input) WHERE id(input) = {inputId}
            MATCH (input)-[:FRIEND]->(friend)
            RETURN friend as item
        ';

        return Statement::create($query, ['inputId' => $input->identity()]);
    }

    public function name() : string
    {
        return 'already_friends';
    }
}
