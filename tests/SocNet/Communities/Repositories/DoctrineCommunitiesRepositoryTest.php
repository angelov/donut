<?php

namespace SocNet\Tests\Communities\Repositories;

use AppBundle\Entity\User;
use AppBundle\Exceptions\ResourceNotFoundException;
use Doctrine\ORM\EntityManager;
use SocNet\Communities\Community;
use SocNet\Communities\Repositories\DoctrineCommunitiesRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineCommunitiesRepositoryTest extends KernelTestCase
{
    /**
     * @var DoctrineCommunitiesRepository
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

        $community = new Community('Example community', $author, 'This is just an example');

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

    /** @test */
    public function it_finds_communities_by_id()
    {
        // @todo extract user creating
        $author = new User();
        $author->setName('John');
        $author->setEmail('john@example.net');
        $author->setPlainPassword('123456');

        $this->em->persist($author);

        $community = new Community('Example community', $author, 'This is just an example');
        $this->em->persist($community);

        $this->em->flush();

        $id = $community->getId();

        $found = $this->repository->find($id);

        $this->assertInstanceOf(Community::class, $found);

        $this->assertSame('Example community', $found->getName());
        $this->assertSame('This is just an example', $found->getDescription());
        $this->assertSame($author, $found->getAuthor());
    }

    /** @test */
    public function it_throws_exception_for_non_existing_ids()
    {
        $this->expectException(ResourceNotFoundException::class);

        $this->repository->find('123');
    }
}