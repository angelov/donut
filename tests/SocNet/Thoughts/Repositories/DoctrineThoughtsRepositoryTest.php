<?php

namespace SocNet\Tests\Thoughts\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use SocNet\Thoughts\Repositories\DoctrineThoughtsRepository;
use SocNet\Thoughts\Thought;
use SocNet\Users\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineThoughtsRepositoryTest extends KernelTestCase
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var DoctrineThoughtsRepository */
    private $repository;

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $this->repository = $kernel->getContainer()->get('app.thoughts.repositories.doctrine');
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /** @test */
    public function it_stores_new_thoughts()
    {
        // @todo extract user creating
        $author = new User('John', 'john@example.net', '123456');

        $this->em->persist($author);
        $this->em->flush();

        $thought = new Thought();
        $thought->setAuthor($author);
        $thought->setContent('something');

        $this->repository->store($thought);

        $id = $thought->getId();

        $found = $this->em->find(Thought::class, $id);

        $this->assertInstanceOf(Thought::class, $found);
        $this->assertSame($thought->getContent(), $found->getContent());
    }
}
