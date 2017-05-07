<?php

namespace SocNet\Tests\Thoughts\Repositories;

use AppBundle\Factories\ThoughtsFactory;
use Doctrine\ORM\EntityManagerInterface;
use SocNet\Thoughts\Repositories\DoctrineThoughtsRepository;
use SocNet\Thoughts\Thought;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineThoughtsRepositoryTest extends KernelTestCase
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var DoctrineThoughtsRepository */
    private $repository;

    /** @var ThoughtsFactory */
    private $thoughtsFactory;

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $this->repository = $kernel->getContainer()->get('app.thoughts.repositories.doctrine');
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->thoughtsFactory = $kernel->getContainer()->get('app.factories.thoughts.faker');
    }

    /** @test */
    public function it_stores_new_thoughts()
    {
        $thought = $this->thoughtsFactory->get();

        $this->repository->store($thought);

        $id = $thought->getId();

        $found = $this->em->find(Thought::class, $id);

        $this->assertInstanceOf(Thought::class, $found);
    }

    /** @test */
    public function it_removes_thoughts_from_database()
    {
        $thought = $this->thoughtsFactory->get();

        $this->repository->store($thought);

        $id = $thought->getId();

        $this->repository->destroy($thought);

        $found = $this->em->find(Thought::class, $id);

        $this->assertNull($found);
    }
}
