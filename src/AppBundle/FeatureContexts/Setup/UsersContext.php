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
}
