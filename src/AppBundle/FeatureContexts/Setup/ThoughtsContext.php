<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace AppBundle\FeatureContexts\Setup;

use Doctrine\ORM\Id\UuidGenerator;
use Angelov\Donut\Behat\Service\Storage\StorageInterface;
use Angelov\Donut\Core\CommandBus\CommandBusInterface;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Thoughts\Commands\StoreThoughtCommand;
use Angelov\Donut\Thoughts\Thought;
use Angelov\Donut\Users\User;
use Angelov\Donut\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;
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
            $author->getId(),
            $content ?? sprintf('Random content #%d', random_int(0, 10000)),
            $latest
        ));

        $this->storage->set('latest_thought_time', $latest);
    }
}
