<?php

namespace AppBundle\FriendsSuggestions;

use GraphAware\Neo4j\Client\Client;
use Angelov\Donut\Users\Events\UserRegisteredEvent;

class RegisterUserResidencyInNeo4j
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function notify(UserRegisteredEvent $event) : void
    {
        $user = $event->getUser();
        $city = $user->getCity();

        $query = '
            MERGE (u:User {id: {userId}, name: {userName}})
            MERGE (c:City {id: {cityId}, name: {cityName}})
            CREATE
                (u)-[:LIVES_IN]->(c)
        ';

        $this->client->run($query, [
            'userId' => $user->getId(),
            'userName' => $user->getName(),
            'cityId' => $city->getId(),
            'cityName' => $city->getName()
        ]);
    }
}
