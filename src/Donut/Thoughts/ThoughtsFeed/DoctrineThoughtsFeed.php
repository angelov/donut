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

namespace Angelov\Donut\Thoughts\ThoughtsFeed;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Angelov\Donut\Core\ResultLists\AbstractDoctrineResultsList;
use Angelov\Donut\Thoughts\Thought;
use Angelov\Donut\Thoughts\ThoughtsFeed\ThoughtsFeedInterface;
use Angelov\Donut\Users\User;

class DoctrineThoughtsFeed extends AbstractDoctrineResultsList implements ThoughtsFeedInterface
{
    private $em;
    private $currentUser;

    private $includeOwnThoughts = true;
    private $filterSource;

    public function __construct(EntityManagerInterface $entityManager, User $currentUser)
    {
        $this->em = $entityManager;
        $this->currentUser = $currentUser;
    }

    public function filterSource(int $source) : void
    {
        $this->filterSource = $source;
    }

    public function includeOwnThoughts(bool $includeOwn = true) : void
    {
        $this->includeOwnThoughts = $includeOwn;
    }

    protected function prepareQuery() : Query
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('thought')
            ->from(Thought::class, 'thought')
            ->join('thought.author', 'author');

        switch ($this->filterSource) {
            case ThoughtsFeedInterface::FROM_FRIENDS:
                $queryBuilder
                    ->where('author in (:friends)')
                    ->setParameter('friends', $this->currentUser->getFriends());
                break;
        }

        if ($this->includeOwnThoughts && $this->filterSource === ThoughtsFeedInterface::FROM_FRIENDS) {
            $queryBuilder
                ->orWhere('author = :current')
                ->setParameter('current', $this->currentUser);
        }

        foreach ($this->getOrderFields() as $orderField) {
            $queryBuilder->addOrderBy($orderField->getField(), $orderField->getDirection());
        }

        return $queryBuilder->getQuery();
    }
}
