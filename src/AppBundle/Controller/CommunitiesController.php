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

use Angelov\Donut\Communities\Commands\JoinCommunityCommand;
use Angelov\Donut\Communities\Commands\LeaveCommunityCommand;
use Angelov\Donut\Communities\Commands\StoreCommunityCommand;
use Angelov\Donut\Communities\Form\CommunityType;
use Angelov\Donut\Communities\Repositories\CommunitiesRepositoryInterface;
use Angelov\Donut\Core\CommandBus\CommandBusInterface;
use Angelov\Donut\Users\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class CommunitiesController extends AbstractController
{
    private $communities;
    private $commandBus;

    public function __construct(CommunitiesRepositoryInterface $communities, CommandBusInterface $commandBus)
    {
        $this->communities = $communities;
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/communities", name="app.communities.index", methods={"GET", "HEAD"})
     */
    public function indexAction() : Response
    {
        $communities = $this->communities->all();

        return $this->render('communities/index.html.twig', [
            'communities' => $communities
        ]);
    }

    /**
     * @Route("/communities/create", name="app.communities.create")
     */
    public function createAction(Request $request) : Response
    {
        $form = $this->createForm(CommunityType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var StoreCommunityCommand $command */
            $command = $form->getData();

            $this->commandBus->handle($command);

            $this->addFlash('success', 'Community was successfully created!');

            return $this->redirectToRoute('app.communities.index');
        }

        return $this->render('communities/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/communities/{id}", name="app.communities.show", methods={"POST", "GET"})
     */
    public function showAction($id) : Response
    {
        $community = $this->communities->find($id);

        return $this->render('communities/show.html.twig', [
            'community' => $community
        ]);
    }

    /**
     * @Route("/communities/{id}/join", name="app.communities.join", methods={"POST"})
     */
    public function joinAction(UserInterface $user, $id) : Response
    {
        /** @var User $user */

        $this->commandBus->handle(new JoinCommunityCommand($user->getId(), $id));

        $this->addFlash('success', 'Successfully joined the community');

        return $this->redirectToRoute('app.communities.index');
    }

    /**
     * @Route("/communities/{id}/leave", name="app.communities.leave", methods={"POST"})
     */
    public function leaveAction(UserInterface $user, $id) : Response
    {
        /** @var User $user */

        $this->commandBus->handle(new LeaveCommunityCommand($user->getId(), $id));

        $this->addFlash('success', 'Successfully left the community');

        return $this->redirectToRoute('app.communities.index');
    }
}
