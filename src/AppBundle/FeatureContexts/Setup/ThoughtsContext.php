<?php

namespace AppBundle\FeatureContexts\Setup;

use AppBundle\Entity\Thought;
use AppBundle\Entity\User;
use AppBundle\FeatureContexts\Storage;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Doctrine\ORM\EntityManager;

class ThoughtsContext implements Context
{
    private $storage;
    private $em;

    public function __construct(EntityManager $entityManager, Storage $storage)
    {
        $this->storage = $storage;
        $this->em = $entityManager;
    }

    /**
     * @Given :name has shared :count thoughts
     */
    public function heHasSharedThoughts(string $name, int $count)
    {
        /** @var User $author */
        $author = $this->storage->get('created_user_' . $name);

        for ($i=0; $i<$count; $i++) {
            $thought = $this->createThought($author);
            $this->em->persist($thought);
        }

        $this->em->flush();
    }

    /**
     * @Given I have shared a :content thought
     */
    public function iHaveSharedAThought(string $content)
    {
        $logged = $this->storage->get('logged_user');
        $thought = $this->createThought($logged, $content);

        $this->em->persist($thought);
        $this->em->flush();
    }

    /**
     * @Given (s)he has shared a :content thought
     */
    public function heHasSharedAThought(string $content)
    {
        $author = $this->storage->get('last_created_user');
        $thought = $this->createThought($author, $content);

        $this->em->persist($thought);
        $this->em->flush();
    }

    private function createThought(User $author, string $content = '')
    {
        $latest = $this->storage->get('latest_thought_time', new \DateTime());

        $thought = new Thought();
        $thought->setAuthor($author);
        $thought->setContent($content ?? sprintf('Random content #%d', random_int(0, 10000)));

        $latest = (clone $latest)->add(new \DateInterval('PT1S')); // add one second to the latest

        $thought->setCreatedAt($latest);
        $this->storage->set('latest_thought_time', $latest);

        return $thought;
    }
}
