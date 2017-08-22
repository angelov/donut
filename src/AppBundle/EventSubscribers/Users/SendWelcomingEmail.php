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

namespace AppBundle\EventSubscribers\Users;

use Angelov\Donut\Users\Events\UserRegisteredEvent;
use Swift_Mailer;
use Twig_Environment;

// @todo add to behat features
// @todo refactor
class SendWelcomingEmail
{
    private $mailer;
    private $twig;

    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function notify(UserRegisteredEvent $event) : void
    {
        $user = $event->getUser();

        $message = \Swift_Message::newInstance()
            ->setSubject('Welcome to the family')
            ->setFrom('send@example.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'users/emails/welcome.html.twig', [
                        'user' => $user
                    ]
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
