<?php

namespace SocNet\Tests\Communities\Repositories;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use SocNet\Communities\Community;
use SocNet\Communities\Repositories\CommunitiesRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommunitiesRepositoryTest extends KernelTestCase
{
    /**
     * @var CommunitiesRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $em;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->repository = $kernel->getContainer()->get('app.communities.repositories.doctrine');
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /** @test */
    public function it_stores_communities()
    {
        // @todo extract user creating
        $author = new User();
        $author->setName('John');
        $author->setEmail('john@example.net');
        $author->setPlainPassword('123456');

        $this->em->persist($author);
        $this->em->flush();

        $community = new Community();
        $community->setName('Example community');
        $community->setDescription('This is just an example');
        $community->setAuthor($author);

        $this->repository->store($community);

        $id = $community->getId();

        $this->assertTrue(is_numeric($id));

        /** @var Community $found */
        $found = $this->em->find(Community::class, $id);

        $this->assertInstanceOf(Community::class, $found);

        $this->assertSame('Example community', $found->getName());
        $this->assertSame('This is just an example', $found->getDescription());
        $this->assertSame($author, $found->getAuthor());
    }
}
