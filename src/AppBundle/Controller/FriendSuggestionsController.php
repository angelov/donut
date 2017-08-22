<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Angelov\Donut\Users\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FriendSuggestionsController extends Controller
{
    /**
     * @Route("/friends/suggestions/{id}/ignore", name="app.friendships.suggestions.ignore")
     */
    public function ignoreAction(User $suggestedUser) : Response
    {
        // @todo refactor

        $query = '
            MATCH 
                (current:User {id: {current}}),
                (suggested:User {id: {suggested}})
            CREATE
                (current)-[:NOT_INTERESTED_TO_BE_FRIEND_WITH]->(suggested)      
        ';

        $client = $this->get('neo4j.client.default');

        $client->run($query, [
            'current' => $this->getUser()->getId(),
            'suggested' => $suggestedUser->getId()
        ]);

        return $this->redirectToRoute('app.friends.index');
    }
}
