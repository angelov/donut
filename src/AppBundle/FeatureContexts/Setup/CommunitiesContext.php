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

use Angelov\Donut\Behat\Service\Storage\StorageInterface;
use Angelov\Donut\Communities\Commands\JoinCommunityCommand;
use Angelov\Donut\Communities\Commands\StoreCommunityCommand;
use Angelov\Donut\Communities\Community;
use Angelov\Donut\Communities\Repositories\CommunitiesRepositoryInterface;
use Angelov\Donut\Core\CommandBus\CommandBusInterface;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Users\User;
use Behat\Behat\Context\Context;

class CommunitiesContext implements Context
{
    private $commandBus;
    private $storage;
    private $uuidGenerator;
    private $communities;

    public function __construct(
        CommandBusInterface $commandBus,
        StorageInterface $storage,
        UuidGeneratorInterface $uuidGenerator,
        CommunitiesRepositoryInterface $communities
    ) {
        $this->storage = $storage;
        $this->uuidGenerator = $uuidGenerator;
        $this->commandBus = $commandBus;
        $this->communities = $communities;
    }

    /**
     * @Given there is a community named :name and described as :description
     * @Given there is a community named :name
     */
    public function thereIsACommunityNamedAndDescribedAs(string $name, string $description = '') : void
    {
        $logged = $this->storage->get('logged_user');

        $this->createCommunity($name, $description, $logged);
    }

    /**
     * @Given he has created the :name community
     */
    public function heHasCreatedTheCommunity(string $name) : void
    {
        $author = $this->storage->get('last_created_user');

        $this->createCommunity($name, '', $author);
    }

    private function createCommunity(string $name, string $description, User $author) : Community
    {
        $id = $this->uuidGenerator->generate();
        $this->commandBus->handle(new StoreCommunityCommand($id, $name, $author, $description));

        $community = $this->communities->find($id);

        $this->storage->set('created_community', $community);
        $this->storage->set('community_' . $name, $community);

        return $community;
    }

    /**
     * @Given I have joined the :name community
     */
    public function iHaveJoinedTheCommunity(string $name) : void
    {
        $community = $this->storage->get('community_' . $name);
        $logged = $this->storage->get('logged_user');

        $this->commandBus->handle(new JoinCommunityCommand($logged, $community));
    }

    /**
     * @Given I have joined it
     */
    public function iHaveJoinedIt() : void
    {
        /** @var Community $community */
        $community = $this->storage->get('created_community');
        $user = $this->storage->get('logged_user');

        $this->commandBus->handle(new JoinCommunityCommand($user, $community));
    }

    /**
     * @Given I haven't joined it
     */
    public function iHavenTJoinedIt() : void
    {
        // do nothing
    }

    /**
     * @Given (s)he has (also) joined it
     */
    public function heAlsoHasJoinedIt() : void
    {
        $community = $this->storage->get('created_community');
        $user = $this->storage->get('last_created_user');

        $this->commandBus->handle(new JoinCommunityCommand($user, $community));
    }

    /**
     * @Given nobody hasn't created any community yet
     */
    public function nobodyHasnTCreatedAnyCommunityYet() : void
    {
        // do nothing
    }
}
