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
use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Friendships\FriendshipRequests\Commands\AcceptFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Commands\CancelFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Commands\DeclineFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Commands\SendFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use Angelov\Donut\Users\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class FriendshipRequestsController extends AbstractController
{
    private $uuidGenerator;
    private $commandBus;

    public function __construct(UuidGeneratorInterface $uuidGenerator, CommandBusInterface $commandBus)
    {
        $this->uuidGenerator = $uuidGenerator;
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/friendships/send/{id}", name="friendships.requests.store", methods={"GET"})
     */
    public function sendFriendshipRequestAction(User $user, UserInterface $currentUser) : Response
    {
        /** @var $currentUser User */

        $id = $this->uuidGenerator->generate();

        $this->commandBus->handle(new SendFriendshipRequestCommand($id, $currentUser->getId(), $user->getId()));

        $this->addFlash('success', 'Friendship request successfully sent!');

        return $this->redirectToRoute('app.friends.index');
    }

    /**
     * @Route("/friendships/cancel/{id}", name="friendships.requests.cancel", methods={"GET"})
     */
    public function cancelFriendshipRequestAction(User $user) : Response
    {
        // @todo use a voter to check if the user can cancel the request

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(FriendshipRequest::class);

        /** @var FriendshipRequest $friendshipRequest */
        $friendshipRequest = $repository->findOneBy([
            'fromUser' => $this->getUser(),
            'toUser' => $user
        ]);

        try {
            $this->commandBus->handle(new CancelFriendshipRequestCommand($friendshipRequest->getId()));
        } catch (ResourceNotFoundException $e) {
            $this->addFlash('error', 'Something went wrong!');
            return $this->redirectToRoute('app.friends.index');
        }

        $this->addFlash('success', 'Friendship request successfully cancelled!');

        return $this->redirectToRoute('app.friends.index');
    }

    /**
     * @Route("/friendships/decline/{id}", name="friendships.requests.decline", methods={"GET"})
     */
    public function declineFriendshipRequestAction(User $user) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(FriendshipRequest::class);

        $friendshipRequest = $repository->findOneBy([
            'fromUser' => $user,
            'toUser' => $this->getUser()
        ]);

        try {
            $this->commandBus->handle(new DeclineFriendshipRequestCommand($friendshipRequest->getId()));
        } catch (ResourceNotFoundException $e) {
            $this->addFlash('error', 'Something went wrong!');
            return $this->redirectToRoute('app.friends.index');
        }

        $this->addFlash('success', 'Friendship request successfully declined!');

        return $this->redirectToRoute('app.friends.index');
    }

    /**
     * @Route("/friendships/{id}", name="friendships.requests.accept")
     */
    public function acceptFriendshipRequestAction(User $user) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(FriendshipRequest::class);

        $friendshipRequest = $repository->findOneBy([
            'fromUser' => $user,
            'toUser' => $this->getUser()
        ]);

        try {
            $this->commandBus->handle(new AcceptFriendshipRequestCommand($friendshipRequest->getId()));
        } catch (ResourceNotFoundException $e) {
            $this->addFlash('error', 'Something went wrong!');
            return $this->redirectToRoute('app.friends.index');
        }

        $this->addFlash('success', 'Friendship request successfully accepted!');

        return $this->redirectToRoute('app.friends.index');
    }
}
