<?php

namespace AppBundle\FeatureContexts\Setup;

use SocNet\Behat\Service\Storage\StorageInterface;
use SocNet\Thoughts\Thought;
use SocNet\Users\User;
use SocNet\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;

class ThoughtsContext implements Context
{
    private $storage;
    private $em;
    private $thoughtsCounter;

    public function __construct(EntityManager $entityManager, StorageInterface $storage, ThoughtsCounterInterface $thoughtsCounter)
    {
        $this->storage = $storage;
        $this->em = $entityManager;
        $this->thoughtsCounter = $thoughtsCounter;
    }

    /**
     * @Given :name has shared :count thoughts
     */
    public function hasSharedThoughts(string $name, int $count) : void
    {
        if (in_array($name, ['he', 'she'])) {
            $author = $this->storage->get('last_created_user');
        } else {
            $author = $this->storage->get('created_user_' . $name);
        }

        for ($i=0; $i<$count; $i++) {
            $thought = $this->createThought($author);
            $this->em->persist($thought);

            $this->thoughtsCounter->increase($author);
        }

        $this->em->flush();
    }

    /**
     * @Given I have shared :count thoughts
     */
    public function iHaveSharedThoughts(int $count) : void
    {
        $author = $this->storage->get('logged_user');

        for ($i=0; $i<$count; $i++) {
            $thought = $this->createThought($author);
            $this->em->persist($thought);

            $this->thoughtsCounter->increase($author);
        }

        $this->em->flush();
    }

    /**
     * @Given I have shared a :content thought
     */
    public function iHaveSharedAThought(string $content) : void
    {
        $logged = $this->storage->get('logged_user');
        $thought = $this->createThought($logged, $content);

        $this->em->persist($thought);
        $this->em->flush();

        $this->thoughtsCounter->increase($logged);
    }

    /**
     * @Given (s)he has shared a :content thought
     */
    public function heHasSharedAThought(string $content) : void
    {
        $author = $this->storage->get('last_created_user');
        $thought = $this->createThought($author, $content);

        $this->em->persist($thought);
        $this->em->flush();
    }

    private function createThought(User $author, string $content = '') : Thought
    {
        $latest = $this->storage->get('latest_thought_time', new \DateTime());

        $thought = new Thought(
            $author,
            $content ?? sprintf('Random content #%d', random_int(0, 10000))
        );

        $latest = (clone $latest)->add(new \DateInterval('PT1S')); // add one second to the latest

        $thought->setCreatedAt($latest);
        $this->storage->set('latest_thought_time', $latest);

        return $thought;
    }
}
