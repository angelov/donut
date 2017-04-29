<?php

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
