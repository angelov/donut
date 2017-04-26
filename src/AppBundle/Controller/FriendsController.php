<?php

namespace AppBundle\Controller;

use AppBundle\FriendsSuggestions\RecommenderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FriendsController extends Controller
{
    /**
     * @Route("/friends", name="app.friends.index", methods={"GET", "HEAD"})
     */
    public function index() : Response
    {
        $user = $this->getUser();
        $suggestedUsers = [];

        try {
            $recommender = new RecommenderService($this->get('neo4j.client.default'));
            $recommendation = $recommender->recommendFriendsForuser($user);

            $users = $this->get('app.users.repository.default');

            foreach ($recommendation->getItems() as $item) {
                $suggestedUsers[] = $users->find($item->item()->value('id'));
            }
        } catch (\Exception $e) {
        }

        return $this->render('friends/index.html.twig', [
            'suggested_users' => $suggestedUsers
        ]);
    }
}
