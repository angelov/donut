<?php

namespace AppBundle\FriendsRecommender;

use AppBundle\Entity\User;

interface FriendsRecommenderInterface
{
    /**
     * @return User[]
     */
    public function recommendFriends(User $user, $count = 2) : array;
}
