<?php

namespace AppBundle\FeatureContexts\Setup;

use Doctrine\ORM\Id\UuidGenerator;
use SocNet\Behat\Service\Storage\StorageInterface;
use SocNet\Core\CommandBus\CommandBusInterface;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Thoughts\Commands\StoreThoughtCommand;
use SocNet\Thoughts\Thought;
use SocNet\Users\User;
use SocNet\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;

class ThoughtsContext implements Context
{
    private $storage;
    private $uuidGenerator;
    private $commandBus;

    public function __construct(StorageInterface $storage, UuidGeneratorInterface $uuidGenerator, CommandBusInterface $commandBus)
    {
        $this->storage = $storage;
        $this->uuidGenerator = $uuidGenerator;
        $this->commandBus = $commandBus;
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
            $this->createThought($author);
        }
    }

    /**
     * @Given I have shared :count thoughts
     */
    public function iHaveSharedThoughts(int $count) : void
    {
        $author = $this->storage->get('logged_user');

        for ($i=0; $i<$count; $i++) {
            $this->createThought($author);
        }
    }

    /**
     * @Given I have shared a :content thought
     */
    public function iHaveSharedAThought(string $content) : void
    {
        $logged = $this->storage->get('logged_user');
        $this->createThought($logged, $content);
    }

    /**
     * @Given (s)he has shared a :content thought
     */
    public function heHasSharedAThought(string $content) : void
    {
        $author = $this->storage->get('last_created_user');
        $this->createThought($author, $content);
    }

    private function createThought(User $author, string $content = '') : void
    {
        $latest = $this->storage->get('latest_thought_time', new \DateTime());
        $id = $this->uuidGenerator->generate();

        $latest = (clone $latest)->add(new \DateInterval('PT1S')); // add one second to the latest

        $this->commandBus->handle(new StoreThoughtCommand(
            $id,
            $author,
            $content ?? sprintf('Random content #%d', random_int(0, 10000)),
            $latest
        ));

        $this->storage->set('latest_thought_time', $latest);
    }
}
