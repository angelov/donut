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

namespace AppBundle\Controller;

use Angelov\Donut\Users\Commands\StoreUserCommand;
use Angelov\Donut\Users\User;
use Angelov\Donut\Users\Form\UserRegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    /**
     * @Route("/register", name="app.users.register")
     */
    public function registerAction(Request $request) : Response
    {
        $form = $this->createForm(UserRegistrationForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var StoreUserCommand $command */
            $command = $form->getData();

            $this->get('app.core.command_bus.default')->handle($command);

            $this->addFlash('success', 'Registration was successful. You many now login.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('users/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users", name="app.users.index")
     */
    public function indexAction() : Response
    {
        $users = $this->get('app.users.repository.default')->all();

        return $this->render('users/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/users/{id}", name="app.users.show", methods={"GET", "HEAD"})
     */
    public function showAction(User $user) : Response
    {
        return $this->render('users/show.html.twig', [
            'user' => $user
        ]);
    }
}
