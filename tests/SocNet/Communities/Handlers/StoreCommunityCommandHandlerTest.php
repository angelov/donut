<?php

namespace SocNet\Tests\Communities\Handlers;

use SocNet\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Bus\MessageBus;
use SocNet\Communities\Commands\StoreCommunityCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StoreCommunityCommandHandlerTest extends KernelTestCase
{
    /** @var MessageBus */
    private $commandBus;

    /** @var EntityManagerInterface */
    private $em;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->commandBus = $kernel->getContainer()->get('command_bus');
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /** @test */
    public function it_handles_store_community_comands()
    {
        // @todo extract user creating
        $author = new User('John', 'john@example.net', '123456');

        $this->em->persist($author);
        $this->em->flush();

        $command = new StoreCommunityCommand('Example', $author);

        $this->commandBus->handle($command);

        // @todo check if the command handler is executed
    }
}
