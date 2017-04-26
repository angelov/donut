<?php

namespace AppBundle\FriendsSuggestions;

use GraphAware\Common\Cypher\Statement;
use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Filter\BaseBlacklistBuilder;

class IgnoredSuggestions extends BaseBlacklistBuilder
{
    public function blacklistQuery(Node $input) : Statement
    {
        $query = '
            MATCH (input) WHERE id(input) = {inputId}
            MATCH (input)-[:NOT_INTERESTED_TO_BE_FRIEND_WITH]->(ignored)
            RETURN ignored as item
        ';

        return Statement::create($query, ['inputId' => $input->identity()]);
    }

    public function name() : string
    {
        return 'ignored_suggestions';
    }
}
