<?php

namespace AppBundle\FeatureContexts\Setup;

use AppBundle\Entity\User;
use AppBundle\FeatureContexts\Storage;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;

class UsersContext implements Context
{
    private $em;
    private $storage;

    public function __construct(EntityManagerInterface $entityManager, Storage $storage)
    {
        $this->em = $entityManager;
        $this->storage = $storage;
    }

    /**
     * @Given there is a user :name with email :email and password :password
     */
    public function thereIsAUserWithEmailAndPassword($name, $email, $password)
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPlainPassword($password);

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
    public function iAm($name)
    {
        $this->storage->set('user_name', $name);
    }

    /**
     * @Given I am registered with email :email and password :password
     */
    public function iAmRegisteredWithEmailAndPassword($email, $password)
    {
        $name = $this->storage->get('user_name', 'John Smith');
        $this->thereIsAUserWithEmailAndPassword($name, $email, $password);
    }

    /**
     * @Given there are users :first, :second, :third and :fourth
     * @Given there are users :first and :second
     */
    public function thereAreUsersWithNames(string ...$names) : void
    {
        foreach ($names as $name) {
            $email = str_replace(' ', '.', strtolower($name));
            $this->thereIsAUserWithEmailAndPassword($name, $email, '123456'); // @todo use a factory or something
        }
    }
}
