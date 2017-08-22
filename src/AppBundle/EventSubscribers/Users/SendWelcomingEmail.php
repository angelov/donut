<?php

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
