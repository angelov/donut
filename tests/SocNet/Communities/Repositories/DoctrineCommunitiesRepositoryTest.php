<?php

namespace SocNet\Tests\Communities\Repositories;

use AppBundle\Factories\CommunitiesFactory;
use AppBundle\Factories\UsersFactory;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Core\Exceptions\ResourceNotFoundException;
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

    /**
     * @var UsersFactory
     */
    private $usersFactory;

    /**
     * @var CommunitiesFactory
     */
    private $communitiesFactory;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->repository = $kernel->getContainer()->get('app.communities.repositories.doctrine');
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->usersFactory = $kernel->getContainer()->get('app.factories.users.faker');
        $this->communitiesFactory = $kernel->getContainer()->get('app.factories.communities.faker');
    }

    /** @test */
    public function it_stores_communities()
    {
        $community = $this->communitiesFactory->get();

        $this->repository->store($community);

        $id = $community->getId();

        $found = $this->em->find(Community::class, $id);

        $this->assertInstanceOf(Community::class, $found);
    }

    /** @test */
    public function it_updates_existing_communities()
    {
        $community = $this->communitiesFactory->get();

        $this->repository->store($community);

        $id = $community->getId();

        $community->setName('Updated name');

        $found = $this->repository->find($id);

        $this->assertSame('Updated name', $found->getName());
    }

    /** @test */
    public function it_finds_communities_by_id()
    {
        $author = $this->usersFactory->get();

        $community = $this->communitiesFactory
            ->withName('Example community')
            ->withDescription('This is just an example')
            ->createdBy($author)
            ->get();

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
        $community = $this->communitiesFactory->get();
        $secondCommunity = $this->communitiesFactory->get();

        $this->em->persist($community);
        $this->em->persist($secondCommunity);

        $this->em->flush();

        $all = $this->repository->all();

        $this->assertTrue(is_array($all));
        $this->assertCount(2, $all);
        $this->assertContains($community, $all);
        $this->assertContains($secondCommunity, $all);
    }
}
