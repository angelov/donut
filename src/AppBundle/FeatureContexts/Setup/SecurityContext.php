<?php

namespace AppBundle\FeatureContexts\Setup;

use Angelov\Donut\Behat\Service\Storage\StorageInterface;
use Angelov\Donut\Core\CommandBus\CommandBusInterface;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Places\City;
use Angelov\Donut\Users\Commands\StoreUserCommand;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
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
    private $commandBus;
    private $users;

    public function __construct(
        EntityManager $entityManager,
        SessionInterface $session,
        Session $minkSession,
        StorageInterface $storage,
        UuidGeneratorInterface $uuidGenerator,
        CommandBusInterface $commandBus,
        UsersRepositoryInterface $users
    ) {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->minkSession = $minkSession;
        $this->storage = $storage;
        $this->uuidGenerator = $uuidGenerator;
        $this->commandBus = $commandBus;
        $this->users = $users;
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
        $name = $this->storage->get('user_name', 'John Smith');

        $this->commandBus->handle(new StoreUserCommand($id, $name, $email, '123456', $city));
        $user = $this->users->find($id);

        $token = new UsernamePasswordToken($user, $user->getPassword(), 'randomstringbutnotnull', $user->getRoles());

        $serializedToken = serialize($token);
        $this->session->set('_security_main', $serializedToken);
        $this->session->save();

        $this->minkSession->setCookie($this->session->getName(), $this->session->getId());

        $this->storage->set('logged_user', $user);
    }
}
