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
use Angelov\Donut\Core\ResultLists\Sorting\OrderDirection;
use Angelov\Donut\Core\ResultLists\Sorting\OrderField;
use Angelov\Donut\Thoughts\ThoughtsFeed\ThoughtsFeedInterface;
use Angelov\Donut\Thoughts\Commands\DeleteThoughtCommand;
use Angelov\Donut\Thoughts\Commands\StoreThoughtCommand;
use Angelov\Donut\Thoughts\Thought;
use Angelov\Donut\Thoughts\Form\ThoughtType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ThoughtsController extends AbstractController
{
    private $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/thoughts", name="app.thoughts.index", methods={"GET", "HEAD", "POST"})
     */
    public function indexAction(Request $request, ThoughtsFeedInterface $thoughtsList) : Response
    {
        $form = $this->createForm(ThoughtType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var StoreThoughtCommand $thought */
            $command = $form->getData();

            $this->commandBus->handle($command);

            $this->addFlash('success', 'Thought shared!');

            return $this->redirectToRoute('app.thoughts.index');
        }

        $page = $request->query->get('page', 1);
        $perPage = 10;
        $offset = ($page-1)*$perPage;

        $thoughtsList->filterSource(ThoughtsFeedInterface::FROM_FRIENDS);
        $thoughtsList->includeOwnThoughts();
        $thoughtsList->orderBy([new OrderField('thought.createdAt', OrderDirection::DESC)]);
        $thoughtsList->setItemsPerPage($perPage);
        $thoughtsList->setOffset($offset);

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'thoughts_list' => $thoughtsList
        ]);
    }

    /**
     * @Route("/thoughts/{id}", name="app.thoughts.delete", methods={"POST"})
     * @todo change to use DELETE method
     */
    public function delete(Thought $thought) : Response
    {
        if (!$this->isGranted('DELETE_THOUGHT', $thought)) {
            return $this->redirectToRoute('app.thoughts.index');
        }

        $this->commandBus->handle(new DeleteThoughtCommand($thought->getId()));

        $this->addFlash('success', 'Thought deleted!');

        return $this->redirectToRoute('app.thoughts.index');
    }
}
