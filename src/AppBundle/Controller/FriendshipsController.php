<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2018, Dejan Angelov <angelovdejan92@gmail.com>
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
 * @copyright Copyright (C) 2016-2018, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace AppBundle\Controller;

use Angelov\Donut\Core\CommandBus\CommandBusInterface;
use Angelov\Donut\Friendships\Commands\DeleteFriendshipCommand;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class FriendshipsController extends AbstractController
{
    private $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/friendships/remove/{id}", name="app.friendships.remove", methods={"GET"})
     * @todo fix to use delete requests
     */
    public function delete(User $user, EntityManagerInterface $em) : Response
    {
        // @todo use custom repository
        $repository = $em->getRepository(Friendship::class);

        if (! $user->isFriendWith($this->getUser())) {
            $this->addFlash('error', 'Can\'t broke a nonexisting friendship.');

            return $this->redirectToRoute('app.friends.index');
        }

        $friendship = $repository->findOneBy([
            'user' => $this->getUser(),
            'friend' => $user
        ]);

        $this->commandBus->handle(new DeleteFriendshipCommand($friendship->getId()));

        $this->addFlash('success', 'Sorry to see broken friendships.');

        return $this->redirectToRoute('app.friends.index');
    }
}
