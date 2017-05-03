<?php

namespace AppBundle\FeatureContexts\Setup;

use SocNet\Behat\Service\Storage\StorageInterface;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Places\City;
use SocNet\Users\User;
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
    private $uuidGenerator;

    public function __construct(
        EntityManager $entityManager,
        SessionInterface $session,
        Session $minkSession,
        StorageInterface $storage,
        UuidGeneratorInterface $uuidGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->minkSession = $minkSession;
        $this->storage = $storage;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @Given I am logged in as :email
     */
    public function iAmLoggedInAs(string $email) : void
    {
        $id = $this->uuidGenerator->generate();
        $city = new City($id, 'Valandovo');
        $this->entityManager->persist($city);

        $id = $this->uuidGenerator->generate();
        $user = new User(
            $id,
            $this->storage->get('user_name', 'John Smith'),
            $email,
            '123456',
            $city
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
