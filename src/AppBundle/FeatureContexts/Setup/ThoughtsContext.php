<?php

namespace AppBundle\FeatureContexts\Setup;

use AppBundle\Entity\Thought;
use AppBundle\Entity\User;
use AppBundle\FeatureContexts\Storage;
use Behat\Behat\Context\Context;
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
     * @Given (s)he has shared :count thoughts
     */
    public function heHasSharedThoughts(int $count)
    {
        /** @var User $author */
        $author = $this->storage->get('created_user');
        $latest = $this->storage->get('latest_thought_time', new \DateTime());

        for ($i=0; $i<$count; $i++) {
            $thought = new Thought();
            $thought->setAuthor($author);
            $thought->setContent(sprintf('Random content #%d', random_int(0, 10000)));

            $latest = (clone $latest)->add(new \DateInterval('PT1S')); // add one second to the latest

            $thought->setCreatedAt($latest);
            $this->storage->set('latest_thought_time', $latest);

            $this->em->persist($thought);
        }

        $this->em->flush();
    }
}
