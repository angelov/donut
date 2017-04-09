<?php

namespace SocNet\Tests\Communities\Repositories;

use SocNet\Users\User;
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
    public function it_updates_existing_communities()
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

        $community->setName('Updated name');

        $found = $this->repository->find($id);

        $this->assertSame('Updated name', $found->getName());
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

    /** @test */
    public function it_returns_array_of_all_communities()
    {
        // @todo extract user creating
        $author = new User();
        $author->setName('John');
        $author->setEmail('john@example.net');
        $author->setPlainPassword('123456');

        $this->em->persist($author);

        $community = new Community('Example community', $author, 'This is just an example');
        $this->em->persist($community);

        $secondCommunity = new Community('Example community 2', $author, 'This is just an example 2');
        $this->em->persist($secondCommunity);

        $this->em->flush();

        $all = $this->repository->all();

        $this->assertTrue(is_array($all));
        $this->assertCount(2, $all);
        $this->assertContains($community, $all);
        $this->assertContains($secondCommunity, $all);
    }
}
