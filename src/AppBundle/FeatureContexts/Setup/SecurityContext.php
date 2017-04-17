<?php

namespace AppBundle\FeatureContexts\Setup;

use SocNet\Users\User;
use AppBundle\FeatureContexts\Storage;
use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

// @todo take a better look on Sylius\Behat\Service\SecurityService

class SecurityContext implements Context
{
    private $entityManager;
    private $session;
    private $minkSession;
    private $storage;

    public function __construct(
        EntityManager $entityManager,
        SessionInterface $session,
        Session $minkSession,
        Storage $storage
    ) {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->minkSession = $minkSession;
        $this->storage = $storage;
    }

    /**
     * @Given I am logged in as :email
     */
    public function iAmLoggedInAs(string $email) : void
    {
        $user = new User(
            $this->storage->get('user_name', 'John Smith'),
            $email,
            '123456'
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $token = new UsernamePasswordToken($user, $user->getPassword(), 'randomstringbutnotnull', $user->getRoles());

        $serializedToken = serialize($token);
        $this->session->set('_security_main', $serializedToken);
        $this->session->save();

        $this->minkSession->setCookie($this->session->getName(), $this->session->getId());

        $this->storage->set('logged_user', $user);
    }
}
