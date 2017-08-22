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

namespace Angelov\Donut\Tests\Communities\Handlers;

use AppBundle\Factories\UsersFactory;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Places\City;
use Angelov\Donut\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Bus\MessageBus;
use Angelov\Donut\Communities\Commands\StoreCommunityCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StoreCommunityCommandHandlerTest extends KernelTestCase
{
    /** @var MessageBus */
    private $commandBus;

    /** @var EntityManagerInterface */
    private $em;

    /** @var UuidGeneratorInterface $uuidGenerator */
    private $uuidGenerator;

    /** @var UsersFactory */
    private $usersFactory;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->commandBus = $kernel->getContainer()->get('command_bus');
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->uuidGenerator = $kernel->getContainer()->get('app.core.uuid_generator');
        $this->usersFactory = $kernel->getContainer()->get('app.factories.users.faker');
    }

    /** @test */
    public function it_handles_store_community_comands()
    {
        $author = $this->usersFactory->get();
        $this->em->persist($author);
        $this->em->flush();

        $id = $this->uuidGenerator->generate();
        $command = new StoreCommunityCommand($id, 'Example', $author);

        $this->commandBus->handle($command);

        // @todo check if the command handler is executed
    }
}
