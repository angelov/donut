<?php

namespace AppBundle\FeatureContexts\Setup;

use SocNet\Behat\Service\Storage\StorageInterface;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Places\City;
use SocNet\Users\User;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UsersContext implements Context
{
    private $em;
    private $storage;
    private $passwordEncoder;
    private $uuidGenerator;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoder $passwordEncoder, StorageInterface $storage, UuidGeneratorInterface $uuidGenerator)
    {
        $this->em = $entityManager;
        $this->storage = $storage;
        $this->passwordEncoder = $passwordEncoder;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @Given there is a user :name with email :email and password :password
     */
    public function thereIsAUserWithEmailAndPassword($name, $email, $password) : void
    {
        $id = $this->uuidGenerator->generate();
        $city = new City($id, 'Valandovo');
        $this->em->persist($city);

        $id = $this->uuidGenerator->generate();
        $user = new User($id, $name, $email, $password, $city);

        $password = $this->passwordEncoder->encodePassword($user, $password);
        $user->setPassword($password);

        $this->em->persist($user);
        $this->em->flush();
        // @todo refactor

        $key = 'created_user_' . $name;
        $this->storage->set($key, $user);

        $this->storage->set('last_created_user', $user);
    }

    /**
     * @Given I am :name
     */
    public function iAm($name) : void
    {
        $this->storage->set('user_name', $name);
    }

    /**
     * @Given I am registered with email :email and password :password
     */
    public function iAmRegisteredWithEmailAndPassword($email, $password) : void
    {
        $name = $this->storage->get('user_name', 'John Smith');
        $this->thereIsAUserWithEmailAndPassword($name, $email, $password);
    }

    /**
     * @Given there are users :first, :second, :third, :fourth, :fifth and :sixth
     * @Given there are users :first, :second, :third and :fourth
     * @Given there are users :first, :second and :third
     * @Given there are users :first and :second
     * @Given there is a user :first
     */
    public function thereAreUsersWithNames(string ...$names) : void
    {
        foreach ($names as $name) {
            $email = str_replace(' ', '.', strtolower($name));
            $this->thereIsAUserWithEmailAndPassword($name, $email, '123456'); // @todo use a factory or something
        }
    }

    /**
     * @Given there is a user :name with email :email
     */
    public function thereIsAUserNameWithEmail(string $name, string $email) : void
    {
        $this->thereIsAUserWithEmailAndPassword($name, $email, '123456');
    }
}
