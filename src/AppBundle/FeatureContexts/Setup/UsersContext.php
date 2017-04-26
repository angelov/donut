<?php

namespace AppBundle\FeatureContexts\Setup;

use SocNet\Users\User;
use AppBundle\FeatureContexts\Storage;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UsersContext implements Context
{
    private $em;
    private $storage;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoder $passwordEncoder, Storage $storage)
    {
        $this->em = $entityManager;
        $this->storage = $storage;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Given there is a user :name with email :email and password :password
     */
    public function thereIsAUserWithEmailAndPassword($name, $email, $password) : void
    {
        $user = new User($name, $email, $password);

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
