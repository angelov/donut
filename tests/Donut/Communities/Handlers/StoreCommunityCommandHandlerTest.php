<?php

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
